<!-- resources/views/import.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Import Excel</title>
</head>
<body>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('test-something') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import Excel</button>
    </form>
</body>
</html>
