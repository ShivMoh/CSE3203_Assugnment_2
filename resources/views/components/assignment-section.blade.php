@vite(['resources/css/app.css','resources/css/assignments.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="assignment-section-body">
        <div class="row">
            <h3>{{$details->title}}</h3>
            <div class="section-preview-form">
                <form  action="/delete-section" method="POST">
                    @csrf
                    <!-- delete -->
                    <input type="hidden" name="id" value="{{$details->id}}">
                    <button type="submit" class="ghost-btn">
                        <i class="fa fa-solid fa-lg fa-x" style="color:#ffffff; width: 10px; height: 10px;"></i>                        
                    </button>
                </form>
            </div>
            
        </div>

        <hr>
        <div class="row">
            <h3>Marks</h3>
            <h4>{{$details->marks_allocated}}</h4>
        </div>

    </div>
</body>


        