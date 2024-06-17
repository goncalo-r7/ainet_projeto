@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
    $url = $administrative->photo_filename;
    
    if($url == null){
        $url = url('storage/photos/anonymous.jpg');
    }else{
        $url = url('storage/photos/'.$url);
    }
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
                        value="{{ old('name', $administrative->name) }}"/>
        <x-field.input name="email" type="email" label="Email" :readonly="$readonly"
                        value="{{ old('email', $administrative->email) }}"/>
        <x-field.radiogroup name="type" label="Type" :readonly="$readonly"
            value="{{ old('type', $administrative->type) }}"
            :options="['A' => 'Administrator', 'C' => 'Customer', 'E' => 'Employee']"/>

    </div>
    <div class="pb-6">
        <x-field.image
            name="photo_file"
            label="Photo"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Photo"
            :deleteAllow="($mode == 'edit') && ($administrative->photo_url)"
            deleteForm="form_to_delete_photo"
            :imageUrl="$url"/>
    </div>
</div>
