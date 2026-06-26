<?php

namespace App\Console\Commands;

use App\Actions\User\DeactivateUserAccount;
use App\Models\User;
use Illuminate\Console\Command;

class DeactivateUserCommand extends Command
{
    protected $signature = 'unionos:deactivate-user {user : User id or email}';

    protected $description = 'Soft-delete a user and dissolve any current couple they belong to.';

    public function __construct(
        private readonly DeactivateUserAccount $deactivateUser,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $identifier = $this->argument('user');
        $user = User::withTrashed()
            ->where('id', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if ($user === null) {
            $this->components->error('User not found.');

            return self::FAILURE;
        }

        $this->deactivateUser->handle($user);
        $this->components->info('User deactivated.');

        return self::SUCCESS;
    }
}
