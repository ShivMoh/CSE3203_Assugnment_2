@vite(['resources/css/app.css','resources/css/assignments.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <x-nav></x-nav>
    <div class="main-content">
        <section class="search">
            <h1 class="title">Assignments</h1>
            <hr>
            <div class="search-bottom">
                <div class="search-container">
                    <form action="/assignments" method="POST" class="search" id="search">
                        @csrf
                        <input 
                            type="text" 
                            name="search" 
                            id="search-bar"
                            class="search-bar"
                            placeholder="Search..."
                            >
                    </form>
                    <form  action="/assignments" method="POST" id="clear">
                        @csrf
                    </form>
                
                    <div class="button-container">
                        <button type="submit" form="search" class="ghost-btn">
                            <i class="fa fa-lg fa-search"></i>

                        </button>
                        <button type="submit" form="clear" class="ghost-btn">
                            <i class="fa fa-solid fa-lg fa-x"></i>                        
                        </button>
                    </div>
                </div>
                <form action="/assignment-add" method="get">
                    <button type="submit" class="add-more">
                        Add More <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            </div>

        </section>

        <section class="assignment-preview">
            <div class="assignment-preview-container">
                @foreach ($content as $assignment)
                    <x-assignment-preview :details="$assignment"/>                
                @endforeach
            </div>
        </section>
    </div>
</body>