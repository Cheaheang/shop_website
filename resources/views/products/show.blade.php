<!doctype html>
<html>
<head><meta charset="UTF-8"><title>{{ $product->name }}</title></head>
<body>
    <h1>{{ $product->name }}</h1>
    <a href="{{ route('products.index') }}">Back</a>
    @if(session('success'))<p>{{ session('success') }}</p>@endif

    <p><strong>Description:</strong> {{ $product->description ?: '-' }}</p>
    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>

    <h2>Images</h2>
    @forelse($product->images as $image)
        <div style="display:inline-block; margin-right:16px; margin-bottom:16px;">
            <img src="{{ asset('storage/'.$image->path) }}" width="180" alt="{{ $image->original_name }}">
            <p>{{ $image->original_name }}</p>
        </div>
    @empty
        <p>No images uploaded.</p>
    @endforelse
</body>
</html>
