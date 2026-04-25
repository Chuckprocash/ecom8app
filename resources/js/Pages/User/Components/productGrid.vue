<script setup>
    import { router, Link, usePage } from '@inertiajs/vue3';
    import Swal from 'sweetalert2';

    const page = usePage();

    const props = defineProps({
        products: Array,
    });
    console.log(props.products)

    function addToCart(product) {
        router.post(route('cart.store', product.id), { quantity: 1 }, {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: page.props.flash.success,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                })
            },
        })
    }

</script>
<template>
    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
        <div v-for="product in products" :key="product.id" class="group relative">
            <img :src="`/storage/${product?.product_images[0]?.image}`" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 
                object-contain group-hover:opacity-75 lg:aspect-auto lg:h-80"  @error="e => e.target.src = '/images/jpgwatermark.jpg'"/>
                <!-- Add to Cart Button on Hover -->
            <div class="absolute inset-0 z-10 flex items-center justify-center pb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <form @submit.prevent="addToCart(product)">
                    <button type="submit"
                        class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2 rounded-md shadow-md hover:bg-indigo-500 transition-colors duration-200">
                        Add to Cart
                    </button>
                </form>
            </div>
            <div class="mt-4 flex justify-between">
                <div>
                    <h3 class="text-sm text-gray-700">
                    <a href="#">
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{ product.title }}
                    </a>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ product.category.name }}</p>
                </div>
                <p class="text-sm font-medium text-gray-900">{{product.price}}$</p>
            </div>
        </div>
    </div>
</template>
<style scoped></style>