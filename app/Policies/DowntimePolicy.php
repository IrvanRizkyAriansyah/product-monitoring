<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Downtime;
use Illuminate\Auth\Access\HandlesAuthorization;

class DowntimePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Downtime');
    }

    public function view(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('View:Downtime');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Downtime');
    }

    public function update(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('Update:Downtime');
    }

    public function delete(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('Delete:Downtime');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Downtime');
    }

    public function restore(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('Restore:Downtime');
    }

    public function forceDelete(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('ForceDelete:Downtime');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Downtime');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Downtime');
    }

    public function replicate(AuthUser $authUser, Downtime $downtime): bool
    {
        return $authUser->can('Replicate:Downtime');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Downtime');
    }

}