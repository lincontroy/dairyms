<?php
// app/Policies/UserPolicy.php
namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    public function manage(User $user)
    {
        return $user->role === 'admin';
    }
}