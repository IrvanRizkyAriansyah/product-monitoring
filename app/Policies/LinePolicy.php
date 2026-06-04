<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Line;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Line');
    }

    public function view(AuthUser $authUser, Line $line): bool
    {
        return $authUser->can('View:Line');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Line');
    }

    public function update(AuthUser $authUser, Line $line): bool
    {
        return $authUser->can('Update:Line');
    }

    public function delete(AuthUser $authUser, Line $line): bool
    {
        return $authUser->can('Delete:Line');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Line');
    }

    public function restore(AuthUser $authUser, Line $line): bool
    {
        return $authUser->can('Restore:Line');
    }

    public function forceDelete(AuthUser $authUser, Line $line): bool
    {
        return $authUser->can('ForceDelete:Line');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Line');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Line');
    }

    public function replicate(AuthUser $authUser, Line $line): bool
    {
        return $authUser->can('Replicate:Line');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Line');
    }

}