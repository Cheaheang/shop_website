<!doctype html>
<html>
<head><meta charset="UTF-8"><title>Edit Product</title></head>
<body>
    <h1>Edit Product</h1>
    <a href="{{ route('products.index') }}">Back</a>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('products._form', ['product' => $product])
    </form>
</body>
</html>
