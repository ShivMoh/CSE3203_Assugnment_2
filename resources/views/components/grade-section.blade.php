@vite(['resources/css/app.css','resources/css/groups/grade.css'])
@vite(['resources/js/app.js'])

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="assignment-section-body">
        <div class="row">
            <h3>{{$gradeSection->name}}</h3>
            <div class="section-preview-form">
                <form  action="" method="POST">
                    @csrf
                    <!-- delete -->
                    <button type="submit" class="ghost-btn">
                        <i class="fa fa-solid fa-lg fa-x" style="color:#ffffff; width: 10px; height: 10px;"></i>                        
                    </button>
                </form>
            </div>            
        </div>

        <hr>
        <div class="row">
            <h3>Total</h3>
            <h3>{{$grade->marks_attained}}</h3>
        </div>
        <div class="row">
            <h3>Marks</h3>

            <form action="/update-grades" method="post">
                @csrf
                <input onchange="this.form.submit()" type="text" name="marks" id="marks" class="marks" placeholder="Enter marks here" value="{{$gradeSection->marks_attained}}">
                <input type="hidden" name="section_id" value="{{$gradeSection->id}}">
                <input type="hidden" name="grade_id" value="{{$grade->id}}">
            </form>
        </div>
        {{$grade->id}}


    </div>
</body>


        