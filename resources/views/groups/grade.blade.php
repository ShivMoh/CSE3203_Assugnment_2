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
                <h1>Breakdown</h1>
                <div class="sections">
                    <h2>Sections</h2>
                    <form  action="" method="POST">
                        @csrf
                        <!-- add section -->
                        <button type="submit" class="ghost-btn">
                            <i class="fa-solid fa-plus"></i>                       
                        </button>
                    </form>
                </div> 
            </div>
            
            <div class="parts">
                <div class="section-parts">
                    @foreach ($grade_sections as $gradeSection)
                        <x-grade-section :gradeSection="$gradeSection" :grade="$grade"></x-grade-section>
                        
                    @endforeach
                </div>
            </div>
            
            <div class="comment-container">
                <h2>Comment</h2>
                <textarea name="" id="" cols="30" rows="10">Something is happening here</textarea>
            </div>


            <div class="details-container">
                <h2>Details</h2>
                <div class="total-score-container row">
                    <div class="title">Total Score</div>
                    <div class="score">100/100</div>
                </div>

                <div class="total-score-container row">
                    <div class="title">Percentage</div>
                    <div class="score">100%</div>
                </div>

                <div class="group-contribution-container">
                    <div class="group-member row">
                        <div class="name">John Doe</div>
                        <div class="contribution">75%</div>    
                    </div>
                </div>
            </div>
        </section>        
            

</body>