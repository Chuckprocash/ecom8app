<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array
});
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap gap-1">
        <Link
            v-for="(link, index) in links"
            :key="index"
            :href="link.url"
            v-html="link.label"
            :class="[
                'px-3 py-2 text-sm border rounded',
                link.active 
                    ? 'bg-blue-600 text-white border-blue-600' 
                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100',
                !link.url ? 'opacity-50 cursor-not-allowed' : ''
            ]"
            :disabled="!link.url"
            preserve-scroll
        />
    </div>
</template>