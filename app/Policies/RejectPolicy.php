<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Reject;
use Illuminate\Auth\Access\HandlesAuthorization;

class RejectPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Reject');
    }

    public function view(AuthUser $authUser, Reject $reject): bool
    {
        return $authUser->can('View:Reject');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Reject');
    }

    public function update(AuthUser $authUser, Reject $reject): bool
    {
        return $authUser->can('Update:Reject');
    }

    public function delete(AuthUser $authUser, Reject $reject): bool
    {
        return $authUser->can('Delete:Reject');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Reject');
    }

    public function restore(AuthUser $authUser, Reject $reject): bool
    {
        return $authUser->can('Restore:Reject');
    }

    public function forceDelete(AuthUser $authUser, Reject $reject): bool
    {
        return $authUser->can('ForceDelete:Reject');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Reject');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Reject');
    }

    public function replicate(AuthUser $authUser, Reject $reject): bool
    {
        return $authUser->can('Replicate:Reject');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Reject');
    }

}