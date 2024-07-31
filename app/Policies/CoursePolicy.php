<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|Teacher|Student $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|Teacher|Student $user, Course $course): bool
    {
        $role = $user->getUserRole();

        return $course->getAttribute($role . '_id');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User|Teacher|Student $user): bool
    {
        return $user->getUserRole() == 'teacher';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|Teacher|Student $user, Course $course): bool
    {
        return $user->getUserRole() == 'teacher' && $course->getAttribute('teacher_id') == $user->getAttribute('id');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->getUserRole() == 'teacher' && $course->getAttribute('teacher_id') == $user->getAttribute('id');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        //
    }
}
