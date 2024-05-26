@vite(['resources/css/app.css','resources/css/groups/groups.css'])
@vite(['resources/js/groups/groups.js'])

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <!-- Pass assignments for the details -->
    <div class="group-preview-body">
        <div class="inner-container">
            <h1>{{$group->name}}</h1>
            <div class="buttons">
                <form  action="" method="POST">
                    @csrf
                    <!-- Forward to group detail -->
                    <button type="button" class="ghost-btn" onclick="expand(@php echo $index; @endphp)">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
                <form  action="" method="POST">
                    @csrf
                    <!-- delete -->
                    <button type="submit" class="ghost-btn">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
                <form  action="" method="POST">
                    @csrf
                    <!-- delete -->
                    <button type="submit" class="ghost-btn">
                        <i class="fa fa-solid fa-lg fa-x"></i>                        
                    </button>
                </form>
            </div>
        </div>

        
        <div class="group-expanded">
            <hr class="line">
            @foreach ($students as $student)
                <div class="group-member-container">
                    <div class="group-member-name">{{$student['student']->first_name}} {{$student['student']->last_name}}</div>
                    <div class="group-member-percentage">25%</div>
                </div>
            @endforeach

        </div>
    </div>

    
</body>