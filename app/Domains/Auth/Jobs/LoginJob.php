<?php

namespace App\Domains\Auth\Jobs;

use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * @return array
     *
     * @throws UnauthorizedException
     */
    public function handle()
    {
        try {
            $user = User::where('email', $this->email)->firstOrFail();
        } catch (ModelNotFoundException $_) {
            throw new UnauthorizedException('Wrong Credentials');
        }

        if (\Hash::check($this->password, $user->password)) {
            return [
                'access_token' => $user->createToken('Authentication Token')->accessToken,
                'user' => $user->makeHidden(['permissions', 'roles'])->append(['allowed_permissions']),
            ];
        } else {
            throw new UnauthorizedException('Wrong Credentials');
        }
    }
}
