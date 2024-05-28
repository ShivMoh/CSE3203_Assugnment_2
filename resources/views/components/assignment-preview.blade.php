@vite(['resources/css/app.css','resources/css/assignments.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <!-- Pass assignments for the details -->
    <div class="assignment-preview-body">
        <h2>{{$details->title}}</h2>
        <div class="buttons">
            <form  action="/detail" method="post">
                @csrf
                <!-- Forward to assignment detail -->
                <input type="hidden" name="id" value="{{$details->id}}">
                <button type="submit" class="ghost-btn">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <!-- PAY ATTENTION HERE KRISTEEN -->
            <form  action="/delete-assignment" method="POST">
                @csrf
                <!-- delete -->
                <input type="hidden" name="id" value="{{$details->id}}">
                <button type="submit" class="ghost-btn">
                    <i class="fa fa-solid fa-lg fa-x"></i>                        
                </button>
            </form>
        </div>
    </div>
</body>