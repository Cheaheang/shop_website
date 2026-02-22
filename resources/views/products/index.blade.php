<!doctype html>
<html>
<head><meta charset="UTF-8"><title>Products</title></head>
<body>
    <h1>Products</h1>
    <a href="{{ route('products.create') }}">Create Product</a>

    @if(session('success'))<p>{{ session('success') }}</p>@endif

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->images->count() }} image(s)</td>
                    <td>
                        <a href="{{ route('products.show', $product) }}">View</a>
                        <a href="{{ route('products.edit', $product) }}">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->links() }}
</body>
</html>
