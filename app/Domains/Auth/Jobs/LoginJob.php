<?php

namespace App\Domains\Auth\Jobs;

use App\Helpers\JsonResponder;
use App\Models\User;

use Lucid\Units\Job;

class LoginJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $user = User::where('email', $this->email)->firstOrFail();
            if (\Hash::check($this->password, $user->password)) {
                \Auth::login($user);
                return \Auth::user();
            }
        } catch (\Exception $_) {
            return JsonResponder::unauthorized('Credentials are not correct', 401);
        }

    }
}
