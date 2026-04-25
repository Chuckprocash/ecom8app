<script setup>
    import Layout from './Layout.vue';
    import ProductGrid from './Components/productGrid.vue';
    import { computed } from 'vue';
    import { usePage, Link } from '@inertiajs/vue3';

    const props = defineProps({
        products: Array,
    });

    const page = usePage();
    //
    const cartCount = computed(() => page.props.cart.data.count);
    // console.log(cartCount.value)

</script>
<template>
    <Layout>
    <section class="bg-neutral-primary">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
            <h1 class="mb-4 mt-8 text-4xl font-bold tracking-tight text-heading md:text-5xl lg:text-6xl">We invest in the world’s potential</h1>
            <p class="mb-8 text-lg font-normal text-body lg:text-xl sm:px-16 xl:px-48">Here at Flowbite we focus on markets where technology, innovation, and capital can unlock long-term value and drive economic growth.</p>
            <div class="flex flex-col mb-8 lg:mb-16 space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                <a href="#" class="text-white bg-cyan-500 box-border border border-transparent hover:bg-cyan-900 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-base px-5 py-3 focus:outline-none">Learn more</a>
                <a href="#" class="inline-flex items-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-base px-5 py-3 focus:outline-none">
                    <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1Zm7 11-6-2V9l6-2v10Z"/>
                    </svg>
                    Watch video
                </a> 
            </div>
    </div>
    </section>
    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Top Selling Items This Week</h2>
            <ProductGrid :products="products"/>
        </div>
    </div>
    <div class="flex justify-center mb-10">
        <Link :href="route('product.index')"
            class="px-8 py-3 rounded-full text-white font-semibold text-sm
                bg-gradient-to-r from-cyan-400 to-cyan-600
                hover:from-cyan-500 hover:to-cyan-700
                shadow-lg hover:shadow-cyan-500/40
                transition-all duration-300 ease-in-out
                hover:scale-105">
            Show All Products
        </Link>
    </div>

    </Layout>
</template>
<style scoped></style>