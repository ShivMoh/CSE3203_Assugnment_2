@vite(['resources/css/app.css','resources/css/groups/grade.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <x-nav></x-nav>
    <div class="main-content">
    <!-- Add content -->


        <section class="details">
            <h1 class="heading">{{$assessment->title}}</h1>
            <p>
                {{$assessment->description}}
            </p>       
         
        </section>

        

        <hr>
        <section class="breakdown">
            <div class="title">
              
                <h1>{{$group->name}}</h1>

                <div class="sections">
                    <h2>Sections</h2>
              
                </div> 
            </div>
            
            <div class="parts">
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
                    <div class="score">100%</div>
                </div>

                <div class="group-contribution-container">
                    <div class="group-member heading-part">
                        <div class="name t-heading">Name</div>
                        <div class="contribution t-heading">Contribution Percent</div>    
                        <div class="contribution t-heading">Calculated Score</div>    

                    </div>

                    @foreach ($students as $student)
                        <div class="group-member">
                            <div class="name">{{$student['student']->first_name}} {{$student['student']->first_name}}</div>
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