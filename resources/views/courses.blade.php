@vite(['resources/css/app.css','resources/css/courses.css','resources/css/app.css'])
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <x-nav></x-nav>
    <div class="main-content">
        <section class="search">
            <h1 class="title">Courses</h1>
            <hr>
            <div class="search-bottom">
                <div class="search-container">
                    <form action="" method="POST" class="search" id="search">
                        @csrf
                        <input 
                            type="search" 
                            name="search" 
                            id="search-bar"
                            class="search-bar"
                            placeholder="Search..."
                        >
                    </form>
                    <form action="" method="POST" id="clear">
                        @csrf
                    </form>
                    <div class="button-container">
                        <button type="submit" form="search" class="ghost-btn">
                            <i class="fa fa-lg fa-search"></i>
                        </button>
                        <button type="submit" form="clear" class="ghost-btn">
                            <i class="fa fa-solid fa-lg fa-x"></i>
                        </button>
                    </div>
                    
                </div>
                <form action="/course-add" method="get">
                    <button type="submit" class="add-more">
                        Add More <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            </div>
              
        </section>
        <section class="assignment-preview">
            <div class="course-container">
                    @foreach ($courses as $course) 
                    <div class="course-card">
                        <div class="course-info">
                            <div class="course-name">{{$course->name}}</div>
                            <div class="course-code">{{$course->code}}</div>
                        </div>
                        <form action="/view" method="post">
                            @csrf
                            <input type="hidden" name="course_id" value="{{$course->id}}">
                            <div class="course-arrow">
                                <button type="submit" class="ghost-btn">
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            </div> 
                        </form>
                            
                    </div>

                    @endforeach
                   
            </div>
        </section>
    </div>
</body>
