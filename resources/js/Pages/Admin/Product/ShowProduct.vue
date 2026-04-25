<script setup>
import AdminLayout from '../Components/Layout.vue';
import ProductView from './ProductView.vue';
import { provide } from 'vue';


const props = defineProps({
    product: Object,
});

//provide('product', props.product);

console.log(props.product)

</script>
<template>
    <AdminLayout>
        <h2 class="text-white">Product</h2>
        <ProductView :product="product"/>
    </AdminLayout>
</template>
<style scoped></style>