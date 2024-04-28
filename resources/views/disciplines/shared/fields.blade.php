@php
    $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp

<div>
    <label for="inputAbbreviation">Abbreviation</label>
    <input type="text" name="abbreviation" id="inputAbbreviation" {{ $disabledStr }} value="{{$discipline->abbreviation}}">
</div>
<div>
    <label for="inputName">Name</label>
    <input type="text" name="name" id="inputName" {{ $disabledStr }} value="{{$discipline->name}}">
</div>
<div>
    <label for="inputName_pt">Name (PT)</label>
    <input type="text" name="name_pt" id="inputName_pt" {{ $disabledStr }} value="{{$discipline->name_pt}}">
</div>
<div>
    <label for="inputCourse">Course</label>
    <select name="course" id="inputCurso" {{ $disabledStr }}>
        @foreach ($courses as $course)
            <option {{ $course->abbreviation == $discipline->course ? 'selected' : '' }}
                    value="{{$course->abbreviation}}">{{$course->name}}</option>
        @endforeach
    </select>
</div>
<div>
    <label for="inputYear">Year</label>
    <input type="text" name="year" id="inputYear" {{ $disabledStr }} value="{{$discipline->year}}">
</div>
<div>
    <label for="inputSemester">Semester</label>
    <input type="text" name="semester" id="inputSemester" {{ $disabledStr }} value="{{$discipline->semester}}">
</div>
<div>
    <label for="inputECTS">ECTS</label>
    <input type="text" name="ECTS" id="inputECTS" {{ $disabledStr }} value="{{$discipline->ECTS}}">
</div>
<div>
    <label for="inputHours">Hours</label>
    <input type="text" name="hours" id="inputHours" {{ $disabledStr }} value="{{$discipline->hours}}">
</div>
<div>
    <label for="inputOptional">Optional</label>
    {{-- This hidden field has the same name as the "optional" field
    and ensures that the "optional" field has always a value ("0" - with hidden field and "1" when optional is checked ) --}}
    <input type="hidden" name="optional" value="0">
    <input type="checkbox" name="optional" id="inputOptional" {{ $disabledStr }} value="1" {{ $discipline->optional ? 'checked' : '' }}>
</div>
