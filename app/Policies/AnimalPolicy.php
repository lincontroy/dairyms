<?php
// app/Policies/AnimalPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Animal;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Animal $animal)
    {
        return true;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Animal $animal)
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Animal $animal)
    {
        return $user->role === 'admin';
    }
}