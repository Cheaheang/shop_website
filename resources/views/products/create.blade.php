<!doctype html>
<html>
<head><meta charset="UTF-8"><title>Create Product</title></head>
<body>
    <h1>Create Product</h1>
    <a href="{{ route('products.index') }}">Back</a>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @include('products._form')
    </form>
</body>
</html>
