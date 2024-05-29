@vite(['resources/css/app.css','resources/css/edit-courses.css'])
<div class="container">
    <div class="course-add-form">
        <h2>Edit Course Name</h2>
        <form action="edit-courses" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{$course->id}}">
            <div class="form-group">
                <label for="name">Course Name:</label>
                <input type="text" id="name" name="name" value="{{ $course->name }}" required>
            </div>
            <div class="form-group">
                <label for="code">Code:</label>
                <input type="string" id="code" name="code" value="{{ $course->code }}" required>
            </div>
            <div class="button-container">
                <button type="submit" class="save-btn">Save</button>
            </div>
        </form>
    </div>
</div>
