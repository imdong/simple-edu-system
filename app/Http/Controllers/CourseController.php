<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $data = Course::query()
            ->usePage();

        return $this->successData($data);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreCourseRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $model = new Course($data);
        $model->saveOrFail();

        return $this->successData($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): \Illuminate\Http\JsonResponse
    {
        return $this->successData($course->append(['student']));
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateCourseRequest $request, Course $course): \Illuminate\Http\JsonResponse
    {
        $course->fill($request->validated());
        $course->saveOrFail();

        return $this->successData($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
