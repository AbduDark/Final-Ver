<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function view(User $user, Store $store)
    {
        return $user->type === 'super_admin' && $store->super_admin_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->type === 'super_admin';
    }

    public function update(User $user, Store $store)
    {
        return $user->type === 'super_admin' && $store->super_admin_id === $user->id;
    }

    public function delete(User $user, Store $store)
    {
        return $user->type === 'super_admin' && $store->super_admin_id === $user->id;
    }
}
