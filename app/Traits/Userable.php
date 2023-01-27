<?php


namespace App\Traits;


use App\Models\Account;
use App\Models\AccountStatusHistory;
use App\Models\Admin;
use App\Models\Chef;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;

trait Userable
{


    public function generateNewToken(array $args = []): array
    {

        $guards = [
            'api' => User::class,

        ];

        $needed_guard = null;

        foreach ($guards as $guard => $class) {
            if (get_class($this) == $class) {
                $needed_guard = $guard;
                break;
            }
        }

        if (!$needed_guard) die("we can't find guard");

        $ttl = 60 * 24 * 30 * 12 * 20; // 20 years for mahmoud

        if (isset($args['expiration_minutes'])) {
            $ttl = $args['expiration_minutes'];
        }

        return [
            'token' => auth($needed_guard)->setTTL($ttl)->login($this),
        ];
    }

    // public function updateStatus(array $args): void
    // {
    //     $status = $args['status'];
    //     $creator_account_id = $args['creator_account_id'];
    //     $reason = $args['reason'];
    //     $updates = $args['updates'] ?? [];

    //     $this->disableLogging();

    //     $this->update(array_merge([
    //         'status' => $status,
    //     ], $updates));

    //     AccountStatusHistory::query()->create([
    //         'account_id' => $this->account_id,
    //         'action' => $status,
    //         'creator_account_id' => $creator_account_id,
    //         'reason' => $reason,
    //     ]);
    // }

    // public
    // function isBlocked()
    // {
    //     return $this->status == "block";
    // }

    // public
    // function isActive()
    // {
    //     return $this->status == "active";
    // }

    // public
    // function isUnderRevision()
    // {
    //     return $this->status == "under_revision";
    // }
}
