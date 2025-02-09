<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UpdatePasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all passwords to use bcrypt hashing algorithm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        // Iterate over each user
        foreach ($users as $user) {
            // Hash the password using bcrypt
            $user->password = Hash::make($user->password);
            $user->save();
        }

        $this->info('All passwords have been updated to use bcrypt hashing algorithm.');
    }
}
