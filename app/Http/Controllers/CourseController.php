<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    public function index(): View
    {
        $allCourses = Course::all();
        debug($allCourses);
        return view('courses.index')->with('courses', $allCourses);
    }

    public function create(): View
    {
        $newCourse = new Course();
        return view('courses.create')->with('course', $newCourse);
    }

    public function store(Request $request): RedirectResponse
    {
        Course::create($request->all());
        return redirect()->route('courses.index');
    }

    public function edit(Course $course): View
    {
        return view('courses.edit')->with('course', $course);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($request->all());
        return redirect()->route('courses.index');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        return redirect()->route('courses.index');
    }

    public function show(Course $course): View
    {
        return view('courses.show')->with('course', $course);
    }
}
