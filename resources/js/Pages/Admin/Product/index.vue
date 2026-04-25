<script setup>
import AdminLayout from '../Components/Layout.vue';
import ProductList from './ProductList.vue';
import { provide, toRef } from 'vue';


const props = defineProps({
    products: Object,
    categories: Array,
    brands: Array,
});

provide('products', toRef(props, 'products'));
provide('categories', props.categories);
provide('brands', props.brands);


</script>
<template>
    <AdminLayout>
        <h2 class="text-white">My Products</h2>
        <ProductList/>
    </AdminLayout>
</template>
<style scoped></style>