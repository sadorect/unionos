<?php

namespace App\Console\Commands;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SeedE2eScenario extends Command
{
    protected $signature = 'unionos:seed-e2e {scenario=phase-3}';

    protected $description = 'Seed deterministic local data for browser E2E scenarios.';

    public function handle(): int
    {
        return match ($this->argument('scenario')) {
            'phase-3' => $this->seedPhaseThree(),
            default => self::FAILURE,
        };
    }

    private function seedPhaseThree(): int
    {
        $alex = User::query()->create([
            'name' => 'Alex',
            'email' => 'alex@example.com',
            'password' => Hash::make('password'),
        ]);

        $jamie = User::query()->create([
            'name' => 'Jamie',
            'email' => 'jamie@example.com',
            'password' => Hash::make('password'),
        ]);

        User::query()->create([
            'name' => 'Sam',
            'email' => 'sam@example.com',
            'password' => Hash::make('password'),
        ]);

        $taylor = User::query()->create([
            'name' => 'Taylor',
            'email' => 'taylor@example.com',
            'password' => Hash::make('password'),
        ]);

        $casey = User::query()->create([
            'name' => 'Casey',
            'email' => 'casey@example.com',
            'password' => Hash::make('password'),
        ]);

        $couple = Couple::query()->create([
            'status' => CoupleStatus::Active,
        ]);

        foreach ([$alex, $jamie] as $index => $user) {
            CoupleMembership::query()->create([
                'couple_id' => $couple->id,
                'user_id' => $user->id,
                'slot' => $index + 1,
                'joined_at' => now(),
                'is_active' => true,
            ]);
        }

        $secondaryCouple = Couple::query()->create([
            'status' => CoupleStatus::Active,
        ]);

        foreach ([$taylor, $casey] as $index => $user) {
            CoupleMembership::query()->create([
                'couple_id' => $secondaryCouple->id,
                'user_id' => $user->id,
                'slot' => $index + 1,
                'joined_at' => now(),
                'is_active' => true,
            ]);
        }

        $this->components->info('Seeded phase-3 E2E scenario.');

        return self::SUCCESS;
    }
}
