<?php

namespace App\Http\Controllers;

use App\Course;
use App\Helpers\Helper;
use App\Http\Requests\CourseRequest;
use App\Mail\NewStudentInCourse;
use App\Review;
use Mail;

class CourseController extends Controller
{
    public function show(Course $course)
    {
        $course->load([
            'category' => function ($q) {
                $q->select('id', 'name');
            },

            'goals' => function ($q) {
                $q->select('id', 'course_id', 'goal');
            },

            'level' => function ($q) {
                $q->select('id', 'name');
            },

            'requirements' => function ($q) {
                $q->select('id', 'course_id', 'requirement');
            },

            'reviews.user',
            'teacher'
        ])->get();

        $related = $course->relatedCourses();

        return view('courses.detail', compact('course', 'related'));
    }

    public function inscribe(Course $course)
    {
        // Se guarda la relacion en BD, luego se crea y envia una instancia de NewStudent... y se retorna a la vista

        $course->students()->attach(auth()->user()->student->id);

        Mail::to($course->teacher->user)->send(new NewStudentInCourse($course, auth()->user()->name));

        return back()->with('message', ['success', __("Inscripto correctamente al curso")]);
    }

    public function subscribed()
    {
        $courses = Course::whereHas('students', function($query) {
            $query->where('user_id', auth()->id());
        }, '=' )->get();

        return view('courses.subscribed', compact('courses'));
    }

    public function addReview(){
        Review::create([
            'user_id' => auth()->id(),
            'course_id' => request('course_id'),
            'rating' => (int) request('rating_input'),
            'comment' => request('message')
        ]);
        return back()->with('message', ['success', __("Muchas gracias por valorar el curso")]);
    }

    public function create()
    {
        $course = new Course;
        $btnText = __("Enviar curso para revision");
        return view('courses.form', compact('course', 'btnText'));
    }

    public function store(CourseRequest $courseRequest)
    {
        $picture = Helper::uploadFile('picture', 'courses');

        $courseRequest->merge(['picture' => $picture]);
        $courseRequest->merge(['teacher_id' => auth()->user()->teacher->id]);
        $courseRequest->merge(['status' => Course::PENDING]);

        Course::create($courseRequest->input());

        return back()->with('message', ['success' => __("Curso enviado correctamente, recibira un correo con cualquier informacion")]);
    }

    public function update()
    {
        //
    }
}
