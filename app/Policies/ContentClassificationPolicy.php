<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ContentClassification;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentClassificationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ContentClassification');
    }

    public function view(AuthUser $authUser, ContentClassification $contentClassification): bool
    {
        return $authUser->can('View:ContentClassification');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ContentClassification');
    }

    public function update(AuthUser $authUser, ContentClassification $contentClassification): bool
    {
        return $authUser->can('Update:ContentClassification');
    }

    public function delete(AuthUser $authUser, ContentClassification $contentClassification): bool
    {
        return $authUser->can('Delete:ContentClassification');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ContentClassification');
    }

    public function restore(AuthUser $authUser, ContentClassification $contentClassification): bool
    {
        return $authUser->can('Restore:ContentClassification');
    }

    public function forceDelete(AuthUser $authUser, ContentClassification $contentClassification): bool
    {
        return $authUser->can('ForceDelete:ContentClassification');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ContentClassification');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ContentClassification');
    }

    public function replicate(AuthUser $authUser, ContentClassification $contentClassification): bool
    {
        return $authUser->can('Replicate:ContentClassification');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ContentClassification');
    }

}