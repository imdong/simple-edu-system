<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UserRoleScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // 不同权限的用户 查询条件不一样
        switch ($user->getUserRole()) {
            case User::USER_ROLE_TEACHER:
                $builder->where('teacher_id', $user->id);
                break;
            case User::USER_ROLE_STUDENT:
                $builder->where('student_id', $user->id);
                break;
        }
    }
}
