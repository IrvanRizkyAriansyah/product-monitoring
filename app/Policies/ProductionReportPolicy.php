<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProductionReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductionReportPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductionReport');
    }

    public function view(AuthUser $authUser, ProductionReport $productionReport): bool
    {
        return $authUser->can('View:ProductionReport');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductionReport');
    }

    public function update(AuthUser $authUser, ProductionReport $productionReport): bool
    {
        return $authUser->can('Update:ProductionReport');
    }

    public function delete(AuthUser $authUser, ProductionReport $productionReport): bool
    {
        return $authUser->can('Delete:ProductionReport');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProductionReport');
    }

    public function restore(AuthUser $authUser, ProductionReport $productionReport): bool
    {
        return $authUser->can('Restore:ProductionReport');
    }

    public function forceDelete(AuthUser $authUser, ProductionReport $productionReport): bool
    {
        return $authUser->can('ForceDelete:ProductionReport');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProductionReport');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProductionReport');
    }

    public function replicate(AuthUser $authUser, ProductionReport $productionReport): bool
    {
        return $authUser->can('Replicate:ProductionReport');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProductionReport');
    }

}