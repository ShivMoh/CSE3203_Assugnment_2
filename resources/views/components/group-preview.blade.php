
<body>
    <!-- Pass assignments for the details -->
    <div class="group-preview-body">
        <div class="inner-container">
            <h1>{{$group->name}}</h1>
            <div class="buttons">
                <form  action="" method="POST">
                    @csrf
                    <!-- Forward to group detail -->
                    <button type="button" class="ghost-btn toggle-button" onclick="expand(@php echo $index; @endphp)">
                        <i class="fa-solid fa-arrow-down"></i>
                    </button>
                </form>
                <form  action="/edit-grades" method="POST">
                    @csrf
                    <!-- delete -->
                    <input type="hidden" name="group_id" value="{{$group->id}}">
                    <button type="submit" class="ghost-btn">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
                <form  action="/delete-group" method="POST">
                    @csrf
                    <!-- delete -->
                    <input type="hidden" name="group_id" value="{{$group->id}}">
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
                    <div class="group-member-percentage">{{$student['contribution']->percentage}}%</div>
                </div>
            @endforeach

        </div>
    </div>

    
</body>