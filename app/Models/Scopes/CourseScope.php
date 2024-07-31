<?php

namespace App\Models\Scopes;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CourseScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        /**
         * @var User|Teacher|Student $user
         */
        $user = Auth::user();

        // 不同权限的用户 查询条件不一样
        switch ($user->getUserRole()) {
            case 'teacher':
                $builder->where('teacher_id', $user->id);
                break;
            case 'student':
                $builder->where('student_id', $user->id);
                break;
        }
    }
}
