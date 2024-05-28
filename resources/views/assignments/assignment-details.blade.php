@vite(['resources/css/app.css','resources/css/assignments.css'])
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
            <p>{{$assessment->description}}</p>       
           
        </section>

        <hr>
        <section class="breakdown">
            <div class="title">
                <h1>Breakdown</h1>
                <div class="sections">
                    <h2>Sections</h2>
                    <form  action="/detail" method="POST">
                        @csrf
                        <!-- add section -->
                        <input type="hidden" name="toggle-save" value="true">
                        <button type="submit" class="ghost-btn">
                            <i class="fa-solid fa-plus"></i>                       
                        </button>
                    </form>
                </div> 
            </div>
            
            <div class="parts">
                <div class="section-parts">
                    @foreach ($sections as $section)
                        <x-assignment-section :details="$section"/>
                    @endforeach
                    
                </div>
                
            </div> 

            <!-- View if Section Add -->
            @if (!empty(request()->input('toggle-save')))
            <div class="section-add">
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
            @endif
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

                <form  action="/groups" method="get">
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
    </script>   

</body>