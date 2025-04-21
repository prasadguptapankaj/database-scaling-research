<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Hashing\Hasher;

class ShardedUserProvider extends EloquentUserProvider
{
    public function __construct(Hasher $hasher, $model)
    {
        parent::__construct($hasher, $model);
    }

    public function retrieveById($identifier)
    {
        $email = Session::get('shard_user_email');

        if (! $email) {
            return null;
        }

        return $this->createModel()->setShardConnection($email)->find($identifier);
    }

    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'] ?? null;

        if (! $email) {
            return null;
        }

        Session::put('shard_user_email', $email);

        return $this->createModel()->setShardConnection($email)
                    ->where('email', $email)
                    ->first();
    }

    public function createModel()
    {
        $class = '\\' . ltrim($this->model, '\\');

        return new $class;
    }
}
