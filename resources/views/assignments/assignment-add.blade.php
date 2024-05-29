@vite(['resources/css/app.css','resources/css/assignments.css', 'resources/css/app.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <x-nav></x-nav>
    <div class="main-content">
        <h1 class="title">
            Add Assignment        
        </h1>
        <div class="assignment-add-form">


            <form action="/assignment-add" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input
                    type="text" 
                    name="title" 
                    id="title" 
                    class="form-control" 
                    placeholder="Enter Assignment Title" 
                    required>
                </div>
                <div class="form-group">
                    <label for="desc">Description:</label>
                    <textarea 
                    type="long-text" 
                    name="desc" 
                    id="desc" 
                    class="form-control" 
                    placeholder="Description" 
                    required></textarea>
                </div>
                <div class="form-group">
                    <label for="marks">Total Marks:</label>
                    <input 
                    type="number" 
                    name="marks" 
                    id="marks" 
                    required>
                </div>
                <div class="form-group">
                    <label for="weight">Course Weight:</label>
                    <input 
                    type="text"
                    placeholder="e.g. 0.2"
                    name="weight" 
                    id="weight" 
                    required>
                </div>

                <div class="form-group">
                    <label for="course_id">Course:</label>
                    <select 
                    name="course_id" 
                    id="course_id" 
                    class="form-control" 
                    required>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id">Category:</label>
                    <select 
                    name="category_id" 
                    id="category_id" 
                    class="form-control" 
                    required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="btns">
                    <button type="submit" class="add-more">Save Assignment</button>
                </div>

                
            </form>
        </div>
    </div>
</body>