@vite(['resources/css/app.css','resources/css/groups/grade.css'])

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="assignment-section-body">
        <div class="row">
            <h3>{{$gradeSection->name}}</h3>
            <div class="section-preview-form">
              
            </div>            
        </div>

        <hr>
        <div class="row">
            <h3>Marks allocated</h3>
            <h3>{{$section->marks_allocated}}</h3>
        </div>
        <div class="row">
            <h3>Marks</h3>

            <form action={{ route('update-grades') }} method="POST">
                @csrf
                <input onchange="this.form.submit()" type="text" name="marks" id="marks" class="marks" placeholder="Enter marks here" value="{{$gradeSection->marks_attained}}">
            
                <input type="hidden" name="section_id" value="{{$gradeSection->id}}">
                <input type="hidden" name="grade_id" value="{{$grade->id}}">
                <input type="hidden" name="marks_allocated" value="{{$section->marks_allocated}}">

            </form>

        </div>
       
    </div>
</body>


        