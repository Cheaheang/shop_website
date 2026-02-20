<template>
  <main class="container">
    <h1>Products</h1>

    <form class="card" @submit.prevent="saveProduct">
      <h2>{{ form.id ? 'Edit Product' : 'Create Product' }}</h2>

      <label>
        Name
        <input v-model="form.name" type="text" required />
      </label>

      <label>
        Description
        <textarea v-model="form.description" rows="3" />
      </label>

      <label>
        Price
        <input v-model.number="form.price" type="number" min="0" step="0.01" required />
      </label>

      <label>
        {{ form.id ? 'Replace Image (optional)' : 'Image' }}
        <input type="file" accept="image/*" @change="onImageChange" :required="!form.id" />
      </label>

      <div class="actions">
        <button type="submit">{{ form.id ? 'Update' : 'Create' }}</button>
        <button v-if="form.id" type="button" class="secondary" @click="resetForm">Cancel</button>
      </div>
    </form>

    <section class="grid">
      <article v-for="product in products" :key="product.id" class="card">
        <img :src="imageUrl(product.image_path)" :alt="product.name" />
        <h3>{{ product.name }}</h3>
        <p>{{ product.description || 'No description.' }}</p>
        <strong>${{ Number(product.price).toFixed(2) }}</strong>

        <div class="actions">
          <button @click="editProduct(product)">Edit</button>
          <button class="danger" @click="deleteProduct(product.id)">Delete</button>
        </div>
      </article>
    </section>
  </main>
</template>

<script setup>
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';

const products = ref([]);
const form = reactive({
  id: null,
  name: '',
  description: '',
  price: '',
  image: null,
});

const fetchProducts = async () => {
  const { data } = await axios.get('/api/products');
  products.value = data;
};

const resetForm = () => {
  form.id = null;
  form.name = '';
  form.description = '';
  form.price = '';
  form.image = null;
};

const onImageChange = (event) => {
  form.image = event.target.files[0] ?? null;
};

const saveProduct = async () => {
  const payload = new FormData();
  payload.append('name', form.name);
  payload.append('description', form.description || '');
  payload.append('price', form.price);

  if (form.image) {
    payload.append('image', form.image);
  }

  if (form.id) {
    payload.append('_method', 'PUT');
    await axios.post(`/api/products/${form.id}`, payload);
  } else {
    await axios.post('/api/products', payload);
  }

  resetForm();
  await fetchProducts();
};

const editProduct = (product) => {
  form.id = product.id;
  form.name = product.name;
  form.description = product.description;
  form.price = Number(product.price);
  form.image = null;
};

const deleteProduct = async (id) => {
  await axios.delete(`/api/products/${id}`);
  await fetchProducts();
};

const imageUrl = (path) => `/storage/${path}`;

onMounted(fetchProducts);
</script>

<style scoped>
.container { max-width: 1000px; margin: 2rem auto; padding: 1rem; font-family: Arial, sans-serif; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; margin-top: 1rem; }
.card { border: 1px solid #ddd; border-radius: 8px; padding: 1rem; background: #fff; display: flex; flex-direction: column; gap: 0.6rem; }
img { width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: 6px; background: #f0f0f0; }
label { display: flex; flex-direction: column; gap: 0.3rem; font-size: 0.95rem; }
input, textarea, button { font: inherit; padding: 0.5rem; }
.actions { display: flex; gap: 0.5rem; }
button { cursor: pointer; border: 1px solid #333; background: #333; color: #fff; border-radius: 6px; }
button.secondary { background: #fff; color: #333; }
button.danger { background: #c62828; border-color: #c62828; }
</style>
