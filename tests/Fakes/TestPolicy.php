<?php

namespace AgilePixels\ResourceAbilities\Tests\Fakes;

use Illuminate\Auth\Access\HandlesAuthorization;

class TestPolicy
{
    use HandlesAuthorization;

    public const VIEWANY = 'viewAny';
    public const VIEW = 'view';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';
    public const RESTORE = 'restore';
    public const FORCEDELETE = 'forceDelete';

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TestModel $test): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, TestModel $test): bool
    {
        return false;
    }

    public function delete(User $user, TestModel $test): bool
    {
        return true;
    }

    public function restore(User $user, TestModel $test, bool $parameter): bool
    {
        return $parameter;
    }

    public function forceDelete(User $user, TestModel $test): bool
    {
        return false;
    }
}
