<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create admin user if not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!User::firstWhere('is_admin', '=', true)) {
            $name = $this->ask('Name');
            $lastName = $this->ask('LastName');
            $email = $this->ask('Email');
            $password = $this->ask('Password');
            $phone = $this->ask('Phone');
            User::create([
                'name' => $name,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'is_admin' => true
            ]);

            $this->info('Пользователь создан!');

        } else {
            $this->error('Пользователь с такими правами уже существует');
        }
    }
}
