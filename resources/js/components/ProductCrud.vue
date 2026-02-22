<script setup>
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';

const products = ref([]);
const form = reactive({
    id: null,
    name: '',
    description: '',
    price: '',
    images: [],
    removeImageIds: [],
});
const loading = ref(false);
const errorMessage = ref('');

const resetForm = () => {
    form.id = null;
    form.name = '';
    form.description = '';
    form.price = '';
    form.images = [];
    form.removeImageIds = [];
    const input = document.getElementById('images');
    if (input) input.value = '';
};

const loadProducts = async () => {
    loading.value = true;
    try {
        const { data } = await axios.get('/api/products');
        products.value = data;
    } finally {
        loading.value = false;
    }
};

const onFileChange = (event) => {
    form.images = Array.from(event.target.files ?? []);
};

const editProduct = (product) => {
    form.id = product.id;
    form.name = product.name;
    form.description = product.description ?? '';
    form.price = String(product.price);
    form.images = [];
    form.removeImageIds = [];
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const submit = async () => {
    errorMessage.value = '';
    const payload = new FormData();
    payload.append('name', form.name);
    payload.append('description', form.description);
    payload.append('price', form.price);

    form.images.forEach((image) => payload.append('images[]', image));
    form.removeImageIds.forEach((id) => payload.append('remove_image_ids[]', id));

    try {
        if (form.id) {
            payload.append('_method', 'PUT');
            await axios.post(`/api/products/${form.id}`, payload);
        } else {
            await axios.post('/api/products', payload);
        }
        await loadProducts();
        resetForm();
    } catch (error) {
        errorMessage.value = error.response?.data?.message ?? 'Failed to save product.';
    }
};

const removeProduct = async (id) => {
    if (!window.confirm('Delete this product?')) return;
    await axios.delete(`/api/products/${id}`);
    await loadProducts();
    if (form.id === id) resetForm();
};

onMounted(loadProducts);
</script>

<template>
    <main class="mx-auto max-w-6xl p-6">
        <h1 class="text-3xl font-bold mb-4">Product CRUD (Vue + MySQL)</h1>
        <p class="mb-6 text-sm text-gray-600">Multiple image upload sample: choose many files at once in the file input below.</p>

        <section class="border rounded p-4 mb-8">
            <h2 class="text-xl font-semibold mb-3">{{ form.id ? 'Edit Product' : 'Create Product' }}</h2>
            <p v-if="errorMessage" class="text-red-600 mb-3">{{ errorMessage }}</p>

            <div class="grid gap-3">
                <input v-model="form.name" class="border rounded p-2" placeholder="Name" type="text" />
                <textarea v-model="form.description" class="border rounded p-2" placeholder="Description"></textarea>
                <input v-model="form.price" class="border rounded p-2" placeholder="Price" step="0.01" min="0" type="number" />
                <input id="images" class="border rounded p-2" type="file" multiple accept="image/*" @change="onFileChange" />
            </div>

            <div v-if="form.id" class="mt-4">
                <p class="font-medium">Existing Images (check to remove)</p>
                <div class="flex flex-wrap gap-4 mt-2">
                    <label v-for="image in products.find((item) => item.id === form.id)?.images ?? []" :key="image.id" class="border rounded p-2 w-44">
                        <img :src="image.url" :alt="image.original_name" class="w-full h-24 object-cover rounded mb-2" />
                        <div class="text-xs break-all mb-2">{{ image.original_name }}</div>
                        <input v-model="form.removeImageIds" :value="image.id" type="checkbox" /> remove
                    </label>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded" @click="submit">Save</button>
                <button v-if="form.id" class="bg-gray-500 text-white px-4 py-2 rounded" @click="resetForm">Cancel</button>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold mb-3">Products</h2>
            <p v-if="loading">Loading...</p>
            <div v-else class="grid md:grid-cols-2 gap-4">
                <article v-for="product in products" :key="product.id" class="border rounded p-4">
                    <h3 class="font-semibold text-lg">{{ product.name }}</h3>
                    <p class="text-sm text-gray-700">{{ product.description || '-' }}</p>
                    <p class="mt-1 font-medium">${{ Number(product.price).toFixed(2) }}</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <img v-for="image in product.images" :key="image.id" :src="image.url" :alt="image.original_name" class="w-20 h-20 object-cover rounded border" />
                    </div>
                    <div class="mt-3 flex gap-2">
                        <button class="bg-amber-500 text-white px-3 py-1 rounded" @click="editProduct(product)">Edit</button>
                        <button class="bg-red-600 text-white px-3 py-1 rounded" @click="removeProduct(product.id)">Delete</button>
                    </div>
                </article>
            </div>
        </section>
    </main>
</template>
