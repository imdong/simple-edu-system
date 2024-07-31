<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Invoice $invoice): bool
    {
        $role = $user->getUserRole();

        return $invoice->getAttribute($role . '_id');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Course $course): bool
    {
        return $user->getUserRole() == 'teacher' && $course->getAttribute('teacher_id') == $user->getAttribute('id');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Invoice $invoice, string $action): bool
    {
        return $this->$action($user, $invoice);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->getUserRole() == 'teacher' && $invoice->getAttribute('teacher_id') == $user->getAttribute('id');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * 发送账单
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function send(User $user, Invoice $invoice): bool
    {
        return $user->getUserRole() == 'teacher' && $invoice->getAttribute('teacher_id') == $user->getAttribute('id');
    }
}
