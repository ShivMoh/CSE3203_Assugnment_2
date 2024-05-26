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
            <h1 class="heading">{Assignment Name}</h1>
            <p>
                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?
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
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                    <x-assignment-section></x-assignment-section>
                </div>
                
            </div> 

            <!-- View if Section Add -->

            <div class="section-add">
                <form action="" method="POST">
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
                <form  action="" method="POST">
                    @csrf
                    <!-- add section -->
                    <button type="submit" class="add-more">
                        Upload Group Reports                       
                    </button>
                </form>
                <form  action="" method="POST">
                    @csrf
                    <!-- add section -->
                    <button type="submit" class="add-more">
                        View Group Reports                   
                    </button>
                </form>
            </div>
        </section>
        </div>

</body>