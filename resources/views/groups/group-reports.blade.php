
@foreach ($groups as $index => $group)

    @php
    
        $students = $student_data[$index]; 
    @endphp


    <x-group-preview :students="$students" :group="$group" :index="$index" ></x-group-preview>
@endforeach
