@vite(['resources/css/app.css','resources/css/groups/groups.css', 'resources/css/assignments.css'])
@vite(['resources/js/groups/groups.js'])

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<x-nav></x-nav>
<div class="main-content">
    <section class="search">
        <h1 class="title">Group Reports</h1>
        <hr>
        <div class="search-bottom">
            <div class="search-container">
                <form  action="group-reports" method="POST" class="search" id="search">
                    @csrf
                    <input 
                        type="search" 
                        name="search" 
                        id="search-bar"
                        class="search-bar"
                        placeholder="Search by name..."
                        >
                </form>
                <form  action="/group-reports" method="POST" id="clear">
                    @csrf
                </form>

                <form action="/group-reports" method="POST" id="assessments">
                    @csrf
                    <div class="input-container">
                        <label for="assessments">Filter by assessments</label>
                        <select name="assessments" id="assessments" class="filter-select" onchange="this.form.submit()">
                            <option value="" selected>All</option>
                            @foreach ($assessments as $assessment)
                                <option value="{{$assessment->id}}">{{$assessment->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </form>

                <form action="/group-reports" method="POST" id="courses">
                    @csrf
                    <div class="input-container">
                        <label for="courses">Filter by course</label>
                        <select name="courses" id="courses" class="filter-select" onchange="this.form.submit()">
                            <option value="" selected>All</option>
                            @foreach ($courses as $course)
                                <option value="{{$course->id}}">{{$course->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    
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
        
        </div>
    
    </section>
    <h2 class="info">Currently showing groups for <strong>Assessment:</strong> {{$a == null ? 'All' : $a->title}} belonging to <strong>Course:</strong> {{$c->name}}</h2>
 
    @foreach ($groups as $index => $group)
    
        @php
        
            $students = $student_data[$index]; 
        @endphp
    
    
        <x-group-preview :students="$students" :group="$group" :index="$index" ></x-group-preview>
    @endforeach
</div>

