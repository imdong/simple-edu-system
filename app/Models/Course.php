<?php

namespace App\Models;

use App\Models\Scopes\UserRoleScope;
use App\Models\Scopes\PaginationScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 课程表
 *
 * @property int $id
 * @property string $name 课程名
 * @property Carbon $date 年月
 * @property int $cost 费用
 * @property int $teacher_id 关联老师ID
 * @property int $student_id 关联学生ID
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Student $student
 */
class Course extends Model
{
    use HasFactory, softDeletes, PaginationScope;

    protected $fillable = [
        'name',
        'date',
        'cost',
        'teacher_id',
        'student_id',
    ];

    /**
     * 自动转换时间
     *
     * @var string[] dates
     */
    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'date'       => 'datetime:Y-m',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public static function boot()
    {
        parent::boot();

        // 只允许查询自己的数据
        static::addGlobalScope(new UserRoleScope());

        static::saving(function ($model) {
            // 保存时修改下时间格式
            $model->date = $model->date->format('Y-m-01');
        });
    }

    /**
     * 添加关联老师字段
     *
     * @return Model
     */
    public function getTeacherAttribute(): Model
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher_id')->first();
    }

    /**
     * 添加关联学生字段
     *
     * @return Model
     */
    public function getStudentAttribute(): Model
    {
        return $this->hasOne(Student::class, 'id', 'student_id')->first();
    }

}
