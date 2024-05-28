@vite(['resources/css/app.css','resources/css/assignments.css', 'resources/css/app.css'])
@vite(['resources/js/assignments.js'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <x-nav></x-nav>
    <div class="main-content">
    <!-- Add content -->
        
        <section class="details">
            <div class="row">
                <div class="">
                    <h1 class="heading">{{$assessment->title}}</h1>
                    <h3><i>{{$course->name}}</i></h3>
                </div>
                <div class="edit-btn">
                    <form action="/assignment-update" method="get">
                        @csrf
                        <button type="submit" class="ghost-btn">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    </form>

                </div>
            </div>
            <p>{{$assessment->description}}</p>       
           
        </section>

        <hr>
        <section class="breakdown">
            <div class="title">
                <h1>Breakdown</h1>
                <div class="sections">
                    <h2>Sections</h2>
                    
                    <button type="submit" class="ghost-btn" id="toggle-section">
                        <i class="fa-solid fa-plus"></i>                       
                    </button>
                </div> 
            </div>

            @if ($errors->any())
                @foreach ($errors as $error)
                <p style="color: red;"> {{$error}}</p>
                
                @endforeach
            @endif
            
            <div class="parts">
                <div class="section-parts">
                    @foreach ($sections as $section)
                        <x-assignment-section :details="$section"/>
                    @endforeach
                    
                </div>
                
            </div> 

            <!-- View if Section Add -->
            <div class="section-add hidden" id="section-add">
                <form action="/assignment-section-add" method="POST">
                    @csrf
                    <div class="row form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <hr>
                    <div class="row form-group">
                        <label for="marks">Marks</label>
                        <input type="number" name="marks">
                    </div>
                    <hr>
                    <button class="add-more" type="submit">
                        Save
                    </button>
                </form>
            </div>
        </section>


        <section class="btns">
            <div class="row group-btns">

                <!-- Add Group Project Reports to this assignment -->

                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="group_report" id="file-input" accept=".xlsx" required>
                    <button type="button" id="upload-button" class="add-more">
                        Upload Group Reports
                    </button>
                    <button type="submit" id="submit-button" style="display: none;">Submit</button>
                </form>

                <form  action="/group-reports" method="get">
                    @csrf
                    <button type="submit" class="add-more">
                        View Group Reports                   
                    </button>
                </form>
            </div>
        </section>
        
    </div>
    <script>
        document.getElementById('upload-button').addEventListener('click', function() {
            document.getElementById('file-input').click();
        });

        document.getElementById('file-input').addEventListener('change', function() {
            document.getElementById('submit-button').click();
        });

        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("toggle-section");
            const toggleSection = document.getElementById("section-add");

            toggleButton.addEventListener("click", function() {
                toggleSection.classList.toggle("hidden");
            });
        });

    </script>   

</body>