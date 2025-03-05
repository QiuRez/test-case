<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class ImportDataFromTypicode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data to DB from jsonplaceholder.typicode.com';

    /**
     * Execute the console command.
     */
    private const URL_USERS = 'https://jsonplaceholder.typicode.com/users';
    private const URL_POSTS = 'https://jsonplaceholder.typicode.com/posts';
    private const URL_COMMENTS = 'https://jsonplaceholder.typicode.com/comments';

    public function handle()
    {

        try {
            $response = Http::get(self::URL_USERS);
            if ($response->ok()) {
                $data = collect($response->json());
                $data = $data->map(function($item) {
                    $item['created_at'] = now();
                    $item['updated_at'] = now();
                    $item['external_id'] = $item['id'];
                    $explodeName = explode(' ', $item['name']);
                    $item['last_name'] = array_pop($explodeName);
                    $item['password'] = bcrypt('hard_pass');
                    unset($item['id'], $item['address'], $item['website'], $item['company'], $item['username']);
                    return $item;
                });
            }
            DB::transaction(function() use($data) {
                User::insert($data->toArray());
            });
            $this->info('Success import Users');
        } catch (Throwable $th) {
            dump($th);
        }


        try {
            $response = Http::get(self::URL_POSTS);
            if ($response->ok()) {
                $users = User::whereNotNull('external_id')->get()->collect();

                $data = collect($response->json());
                $data = $data->map(function($item) use($users) {
                    $item = collect($item);
                    if ($user = $users->firstWhere('external_id', '=', $item->get('userId'))) {
                        $item->put('created_at', now());
                        $item->put('updated_at', now());
                        $item->put('external_id', $item->get('id'));
                        $item->put('description', str_replace("\n", '',  $item->get('body')));
                        $item->put('user_id', $user->id);
                        unset($item['body'], $item['userId']);
                        return $item->toArray();
                    }
                });
            }
            DB::transaction(function() use($data) {
                Post::insert($data->toArray());
            });
            $this->info('Success import Posts');
        } catch (Throwable $th) {
            dump($th);
        }




        try {
            $response = Http::get(self::URL_COMMENTS);
            if ($response->ok()) {
                $posts = Post::whereNotNull('external_id')->get()->collect();

                $data = collect($response->json());
                $data = $data->map(function($item) use($posts) {
                    $item = collect($item);
                    if ($post = $posts->firstWhere('external_id', '=', $item->get('postId'))) {
                        $item->put('created_at', now());
                        $item->put('updated_at', now());
                        $item->put('external_id', $item->get('id'));
                        $item->put('description', str_replace("\n", '',  $item->get('body')));
                        $item->put('post_id', $post->id);
                        unset($item['body'], $item['postId'], $item['id']);
                        return $item->toArray();
                    }
                });
            }
            DB::transaction(function() use($data) {
                Comment::insert($data->toArray());
            });
            $this->info('Success import Comments');
        } catch (Throwable $th) {
            dump($th);
        }


    }
}
