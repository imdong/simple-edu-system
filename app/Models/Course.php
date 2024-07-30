<?php

namespace App\Models;

use App\Scopes\PaginationScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 课程表
 *
 * @property int $id
 * @property string $name 课程名
 * @property Carbon $date 年月
 * @property string $cost 费用
 * @property int $student_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Student $student
 */
class Course extends Model
{
    use HasFactory, PaginationScope;

    protected $fillable = [
        'name',
        'date',
        'cost',
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
    ];

    protected $casts = [
        'date'       => 'datetime:Y-m',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $appends = [
        'student'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->date = $model->date->format('Y-m-01');
        });
    }

    public function getStudentAttribute(): Model
    {
        return $this->hasOne(Student::class, 'id', 'student_id')->first();
    }

}
