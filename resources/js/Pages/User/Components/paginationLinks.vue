<script setup>
import { Link } from '@inertiajs/vue3';


const props = defineProps({
    products: Object,
});
console.log(props.products)
</script>
<template>
    <div class="flex gap-1 justify-center mt-8">
        <Link v-for="link in products.meta.links" :key="link.label"
            v-html="link.label"
            :href="link.url ?? '#'"
            :class="link.active ? 'bg-indigo-600 text-white' : 'text-gray-700'"
            class="px-4 py-2 text-sm rounded-md border"
            preserve-scroll
        />
    </div>
</template>
<style scoped></style>