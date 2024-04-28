<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Discipline;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $disciplines = Discipline::all();
        return view('disciplines.index', compact('disciplines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $discipline = new Discipline();
        $courses = Course::all();
        return view('disciplines.create')
            ->with('discipline', $discipline)
            ->with('courses', $courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Discipline::create($request->all());
        return redirect()->route('disciplines.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Discipline $discipline): View
    {
        $courses = Course::all();
        return view('disciplines.show')
            ->with('discipline', $discipline)
            ->with('courses', $courses);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discipline $discipline): View
    {
        $courses = Course::all();
        return view('disciplines.edit', compact('discipline', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discipline $discipline): RedirectResponse
    {
        $discipline->update($request->all());
        return redirect()->route('disciplines.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discipline $discipline): RedirectResponse
    {
        $discipline->delete();
        return redirect()->route('disciplines.index');
    }
}
