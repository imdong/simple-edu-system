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
    public function index()
    {
        $data = Course::query()
            ->usePage();

        return $this->successData($data);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        $model = new Course($data);
        $model->saveOrFail();

        return $this->successData($model);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return $this->successData($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
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
