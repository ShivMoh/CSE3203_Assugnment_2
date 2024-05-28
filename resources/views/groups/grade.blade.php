@vite(['resources/css/app.css','resources/css/groups/grade.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <x-nav></x-nav>
    <div class="main-content">
    <!-- Add content -->

       

        <form  class="back-button" action="/group-reports" method="POST">
            @csrf
            <button type="submit" class="ghost-btn">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            Go back
        </form>

        <section class="details">
            <h1 class="heading">{{$assessment->title}}</h1>
            <p>
                {{$assessment->description}}
            </p>       
         
        </section>

    
        <hr>
        <section class="breakdown">
            <div class="title">
              
                <div class="title-container">
                    <h1>{{$group->name}}</h1>

                    <form action="/export_grades" method="POST">
                        @csrf
                        <input type="hidden" name="group_id" value="{{$group->id}}" >
                        <button>Export grades</button>
                    </form>

                    <form action="{{ route('import-grades') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" id="file" required onchange="this.form.submit()">
                        <input type="hidden" name="group_id" value="{{$group->id}}" >
                    </form>
                </div>
               
                <div class="sections">
                    <h2>Sections</h2>
                </div> 
            </div>
            
            <div class="parts">
                <div class="row">
                    @if ($errors->has('marks'))
                        <span class="error">Marks is a required field</span>
                    @endif
                    @if ($errors->has('marks_overflow'))
                        <span class="error">{{ $errors->first('marks_overflow')}}</span>
                    @endif
                    @if ($errors->has('sections_overflow'))
                    <span class="error">{{ $errors->first('sections_overflow')}}</span>
                    @endif

                    @if ($errors->has('structure_error'))
                    <span class="error">{{ $errors->first('structure_error')}}</span>
                @endif
                </div>
                <div class="section-parts">
                    
                    @foreach ($grade_sections as $index => $gradeSection)
                        <x-grade-section :gradeSection="$gradeSection" :grade="$grade" :section="$sections[$index]"></x-grade-section>
                        
                    @endforeach
                </div>

           
            </div>
            
            <div class="comment-container">
                <h2>Comment</h2>

                <form action="/update-comment" method="POST">
                    @csrf
                    <input type="hidden" name="grade_id" value="{{$grade->id}}">
                    <textarea name="comment" id="comment" cols="30" rows="10" onchange="this.form.submit()">{{$comment->comment}}</textarea>
                </form>
            </div>


            <div class="details-container">
                <h2>Details</h2>
                <div class="total-score-container row">
                    <div class="title">Total Score</div>
                    <div class="score">{{$grade->marks_attained}} / {{$assessment->total_marks}}</div>
                </div>

                <div class="total-score-container row">
                    <div class="title">Percentage</div>
                    <div class="score">{{($grade->marks_attained / $assessment->total_marks) * 100}} %</div>
                </div>

                <div class="group-contribution-container">
                    <div class="group-member heading-part">
                        <div class="name t-heading">Name</div>
                        <div class="contribution t-heading">Contribution Percent</div>    
                        <div class="contribution t-heading">Calculated Score</div>    

                    </div>

                    @foreach ($students as $student)
                        <div class="group-member">
                            <div class="name">{{$student['student']->first_name}} {{$student['student']->last_name}}</div>
                            <div class="contribution">{{$student['contribution']->percentage}}</div>    
                            @php
                                $contribution_percentage = $student['contribution']->percentage;
                                $personal_score = $contribution_percentage/100 * $grade->marks_attained;
                            @endphp
                            <div class="contribution">{{$personal_score}}</div>    

                        </div>
                    @endforeach
                   
                </div>
            </div>
        </section>        
            

</body>