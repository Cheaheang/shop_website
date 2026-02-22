@csrf

<div>
    <label>Name</label><br>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div>
    <label>Description</label><br>
    <textarea name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div>
    <label>Price</label><br>
    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" required>
</div>

<div>
    <label>Upload Images (multiple)</label><br>
    <input type="file" name="images[]" multiple accept="image/*">
    <p>Sample: choose 2-3 images at once, then submit. Files are stored in <code>storage/app/public/products</code>.</p>
</div>

@if(isset($product) && $product->images->isNotEmpty())
    <div>
        <p>Existing Images (check to remove):</p>
        @foreach($product->images as $image)
            <label style="display:block; margin-bottom:8px;">
                <input type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}">
                Remove {{ $image->original_name }}
                <br>
                <img src="{{ asset('storage/'.$image->path) }}" width="120" alt="{{ $image->original_name }}">
            </label>
        @endforeach
    </div>
@endif

<button type="submit">Save</button>
