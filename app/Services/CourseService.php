<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Foundation\Auth\User;

class CourseService extends BaseService
{

    /**
     * @param array $data
     * @param User $user
     * @return Course
     */
    public static function create(array $data, User $user): Course
    {
        $model = new Course();

        $model->fill($data);
        $model->teacher_id = $user->id;

        return $model;
    }
}
