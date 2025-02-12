<?php

namespace App\Http\Controllers;

use App\Exceptions\OperationDeniedException;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws OperationDeniedException
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('viewAny', Course::class)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        // 查询对象
        $query = Course::query();

        // 查询条件
        $params = $request->validate([
            'name'    => ['string', 'nullable'],
            'invoice' => ['boolean', 'filled']
        ]);

        // 查询参数
        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        // 只查未关联账单的
        if (isset($params['invoice'])) {
            $params['invoice'] ? $query->has('invoice') : $query->whereDoesntHave('invoice');
        }

        $data = $query->with(['student', 'teacher', 'invoice'])
            ->usePage();

        return $this->successData($data);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreCourseRequest $request): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('create', Course::class)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        $data = $request->validated();

        $model = CourseService::create($data, $user);
        $model->saveOrFail();

        return $this->successData($model);
    }

    /**
     * Display the specified resource.
     * @throws OperationDeniedException
     */
    public function show(Request $request, Course $course): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('view', $course)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        return $this->successData($course->append(['student', 'teacher', 'invoice']));
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateCourseRequest $request, Course $course): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('update', $course)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        $course->fill($request->validated());
        $course->saveOrFail();

        return $this->successData($course);
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Request $request, Course $course): \Illuminate\Http\JsonResponse
    {
        /**
         * @var  User $user
         */
        $user = $request->user();
        if ($user->cannot('delete', $course)) {
            throw new OperationDeniedException('无权限执行该操作');
        }

        $course->deleteOrFail();

        return $this->success();
    }
}
