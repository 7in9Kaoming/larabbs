<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    /**
     * 确定用户是否是本人
     *
     * @param  \App\User  $user
     * @param  \App\User  $user1
     * @return bool
     */
    public function update(User $user, User $user1)
    {
        return $user->id === $user1->id;
    }
}
