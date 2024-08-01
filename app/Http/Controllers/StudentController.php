<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $name = $request->input('name');
        $page = $request->input('page');

        $query = Student::query();

        if (!empty($name)) {
            $query->where('name', 'like', '%' . $name . '%');
            $query->orWhere('username', 'like', '%' . $name . '%');
        }

        if ($page == 'all') {
            $students = [
                'data' => $query->get()
            ];
        } else {
            $students = $query->usePage($page);
        }

        return $this->successData($students);
    }
}
