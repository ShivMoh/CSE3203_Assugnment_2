@vite(['resources/css/app.css','resources/css/assignments.css'])
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <x-nav></x-nav>
    <div class="main-content">
        <h1 class="title">Add Assignment</h1>
        <div class="assignment-add-form">
            <form action="" method="">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Assignment Name">
                </div>
                <div class="form-group">
                    <label for="desc">Description:</label>
                    <textarea type="long-text" name="desc" id="desc" class="form-control" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="marks">Total Marks:</label>
                    <input type="number" name="marks" id="marks">
                </div>
                <div class="form-group">
                    <label for="weight">Course Weight:</label>
                    <input type="number" name="weight" id="weight">
                </div>

                <div class="btns">
                    <button type="submit" class="add-more">Save Assignment</button>
                </div>

                
            </form>
        </div>
    </div>
</body>