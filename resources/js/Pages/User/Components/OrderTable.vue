<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
//
const props = defineProps({
    order: Object,
});

// console.log(props.order)
const dropdown = ref(false);
//
function retryCheckout(order) {
    router.post(route('checkout.order', order.id), {}, {
        onError: (errors) => {
            console.log(errors)
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'error',
                title: errors.message ?? 'Something went wrong.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            })
        },
        onSuccess: () => {
            console.log('success')
        }
    })
}


</script>
<template>
    <div class="w-full max-w-screen-xl mx-auto">
    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
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
                    <th scope="col" class="px-6 py-3 font-medium">
                        Price ${{ order.total_price }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        <span @click="retryCheckout(order)" v-if="order.status !== 'paid'" id="badge-avatar-dismiss-success" class="cursor-pointer inline-flex items-center bg-success-soft border border-success-subtle text-fg-success-strong text-xs font-medium ps-1.5 pe-0.5 py-0.5 rounded gap-1">
                            <img class="w-3.5 h-3.5 rounded-full me-1" src="https://thispersondoesnotexist.com/" alt="Rounded avatar">
                            Proceed to payment
                            <button type="button" class="inline-flex items-center p-0.5 text-sm bg-transparent rounded-xs hover:bg-success-medium" data-dismiss-target="#badge-avatar-dismiss-success" aria-label="Remove">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                            <span class="sr-only">Remove badge</span>
                            </button>
                        </span>
                        <span v-else>ThankYou for Purchasing</span>
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