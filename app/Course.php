<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Course
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $category_id
 * @property int $level_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string|null $picture
 * @property string $status
 * @property bool $previous_approved
 * @property bool $previous_rejected
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePreviousApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course wherePreviousRejected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Course whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Course extends Model
{
    // Trait, hace que al realizar busquedas se ignoren los campos marcados como deleted_at, implementa el borrado logico
    use SoftDeletes;

    protected $fillable = ['teacher_id', 'name', 'description', 'picture', 'level_id', 'category_id', 'status', 'slug'];

    const PUBLISHED = 1;

    const PENDING = 2;

    const REJECTED = 3;

    protected $withCount = ['reviews', 'students'];

    /** Evento saved se ejecuta luego de haber creado o actualizado **/
    public static function boot()
    {
        parent::boot();

        static::creating(function (Course $course){
            if (! \App::runningInConsole()){
                $course->slug = str_slug($course->name, '-');
            }
        });

        static::saved(function (Course $course){
            // Si no se esta corriendo en la consola
            if (! \App::runningInConsole()){
                // Si la peticion esta trayendo requisitos
                if (request('requirements')){
                    foreach (request('requirements') as $key => $requirement_input){
                        if ($requirement_input){
                            Requirement::updateOrCreate(['id' => request('requirement_id'. $key)], [
                                'course_id' => $course->id,
                                'requirement' => $requirement_input
                            ]);
                        }
                    }
                }

                if (request('goals')){
                    foreach (request('goals') as $key => $goal_input){
                        if ($goal_input){
                            Goal::updateOrCreate(['id' => request('goal_id'. $key)], [
                                'course_id' => $course->id,
                                'goal' => $goal_input
                            ]);
                        }
                    }
                }
            }
        });
    }

    /**
     *  direcccion de la imagen del curso
     *
     */
    public function pathAttachment()
    {
        return "/images/courses/" . $this->picture;
    }

    /**
     * ruta mediante slug
     *
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    /**
     * Relationships
     */
    public function category()
    {
        return $this->belongsTo(Category::class)->select('id', 'name');
    }

    public function goals()
    {
        return $this->hasMany(Goal::class)->select('id', 'course_id', 'goal');
    }

    public function level()
    {
        return $this->belongsTo(Level::class)->select('id', 'name');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->select('id', 'user_id', 'course_id', 'rating', 'comment', 'created_at');
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class)->select('id', 'course_id', 'requirement');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Obtiene el avg del rating
     */
    public function getCustomRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    /**
     * Obtiene los cursos relacionados
     */
    public function relatedCourses()
    {
        return Course::with('reviews')->whereCategoryId($this->category_id)
            ->where('id', '!=', $this->id)
            ->latest()->limit(6)->get();
    }
}
