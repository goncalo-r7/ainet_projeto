@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
    $input_filler = old('name', $theater->name) ;
    $url = $theater->photo_filename ?? "unknown.png";
    $url = url('storage/theaters/'.$url);
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
                        value="{{$input_filler}}"/>
    </div>
    <div class="pb-6">
        <x-field.image
            name="photo_filename"
            label="Photo"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Photo"
            :deleteAllow="($mode == 'edit') && ($theater->photo_filename)"
            deleteForm="form_to_delete_photo"
            :imageUrl="$url"/>
    </div>
</div>
