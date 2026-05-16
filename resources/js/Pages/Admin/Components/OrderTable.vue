<script setup>
import { ref } from 'vue';

const props = defineProps({
    order: Object,
});

const dropdown = ref(false);



</script>
<template>
    <div class="w-full max-w-screen-xl mx-auto dark:text-white">
    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body dark:bg-gray-700">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th scope="col" class="px-16 py-3">
                        <span @click="dropdown = !dropdown" class="cursor-pointer">Order#{{ order.id }}</span>
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Product
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Qty
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium w-40">
                        Price ${{ order.total_price }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium w-32">
                        <span :class="order.payment.status == 'paid' ? 'text-green-400' : 'text-red-500' ">{{ order.payment.status }}</span>
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium w-32">
                        <span :class="order.status == 'unpaid' ? 'text-red-500' : 'text-green-400' ">{{ order.status }}</span>
                    </th>
                </tr>
            </thead>
            <tbody :class="{'hidden': !dropdown}">
                <tr v-for="item in order.order_items" class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium">
                    <td class="p-1 flex justify-center">
                        <img v-if="!item.product?.product_images.length" src="/images/jpgwatermark.jpg" class="w-12 md:w-16 max-w-full max-h-full" alt="Apple Watch">
                        <img v-else :src="`/storage/${item.product.product_images[0].image}`" class="w-12 md:w-16 max-w-full max-h-full" alt="Apple Watch">
                    </td>
                    <td class="px-6 py-4 font-semibold text-heading">
                        {{ item.product.title }}
                    </td>
                    <td class="px-6 py-4">
                        {{item.quantity}}
                    </td>
                    <td class="px-6 py-4 font-semibold text-heading">
                        ${{ item.unit_price }}
                    </td>
                    <td class="px-6 py-4">
                        <span v-if="order.status == 'unpaid'" class="bg-brand-softer text-fg-brand-strong text-xs font-medium px-1.5 py-0.5 rounded">Unpaid</span>
                        <span v-else class="bg-success-soft text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded">Paid</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</template>