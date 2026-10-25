<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    protected $signature = 'unionos:make-admin {email : The email of the user to promote}';

    protected $description = 'Promote a user to admin by email address.';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::withTrashed()->where('email', $email)->first();

        if ($user === null) {
            $this->components->error("No user found with email: {$email}");
            return self::FAILURE;
        }

        $user->update(['is_admin' => true]);
        $this->components->info("User {$user->name} ({$email}) is now an admin.");

        return self::SUCCESS;
    }
}
