<script setup>
import Layout from '@/Pages/Admin/Components/Layout.vue';
import Pagination from '@/Pages/Components/Pagination.vue';
import OrderTable from '../Components/OrderTable.vue';

const props = defineProps({
    orders: Object,
});

console.log(props.orders)
</script>
<template>
    <Layout>
        <h2 class="my-5 text-xl dark:text-slate-200">All orders</h2>
        <div class="w-full max-w-screen-xl mx-auto dark:text-white">
        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body dark:bg-gray-800">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-default-medium">
                <tr>
                    <th scope="col" class="px-16 py-3">
                        <span>Order ID</span>
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Product
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Qty
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium w-40">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium w-32">
                        <span >Payment Status </span>
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium w-32">
                        <span > Order Status </span>
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
        </div>
        <OrderTable v-for="order in orders.data" :order="order"/>
        <div class="mt-4">
            <Pagination :links="orders.links" />
        </div>
    </Layout>
</template>
<style scoped></style>