@vite(['resources/css/app.css','resources/css/courses.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <x-nav></x-nav>
    <div class="main-content">
        <h1 class="title">Add Course</h1>
        <div class="course-add-form">

            <form action="/courses-add" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Assignment Title" required>
                </div>
                <div class="form-group">
                    <label for="desc">Code:</label>
                    <textarea type="long-text" name="code" id="code" class="form-control" placeholder="Enter Course Code" required></textarea>
                </div>
                <div class="btns">
                    <button type="submit" class="add-more">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</body>
