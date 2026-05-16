<script setup>
import Layout from './Layout.vue';
import OrderTable from './Components/OrderTable.vue';


//
const props = defineProps({
    orders: Array,
});

console.log(props.orders)
</script>
<template>
    <Layout>
        <!-- <pre>you have orders</pre> -->
        <h2>Your Orders</h2>
        <OrderTable v-for="order in orders" :order="order"/>
    </Layout>
</template>
<style scoped></style>