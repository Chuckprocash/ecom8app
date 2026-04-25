<script setup>
import { inject, ref, onMounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';

const form = useForm();

const props = defineProps({
    product: Object,
});

// const product = inject('product');
// console.log(product)

const fileInput = ref(null);

const selectedImage = ref();

const deleteProductImage = (id) => {
    
    //onConfirm()
    if (confirm('Are you sure you want to delete this image?')) {
        form.delete(`/admin/product_images/${id}`,{
            onSuccess: () => {
            selectedImage.value = null;
            console.log(selectedImage.value)
            router.reload({ only: ['product'] })  // only re-fetches products prop 
            },
            onError: (errors) => console.log(errors)  // handle errors
        });
    }
};

const imageSelect = (image, event) => {
    event.stopPropagation();  // Prevent click from bubbling to container
    selectedImage.value = image;
}

// Handle clicks outside images
const handleContainerClick = (event) => {
    // Check if click is directly on the grid container (not on any image)
    if (!event.target.classList.contains('image-grid-container')) {
        selectedImage.value = null;
    }
};


// Trigger file input click
const triggerFileInput = () => {
    fileInput.value.click();
};

// Auto-upload on file select
const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);

    router.post(`/admin/products/${props.product.id}/images`, formData, {
        preserveScroll: true,
        onSuccess: () => {
            event.target.value = ''; // Reset input
            router.reload();
        },
        onError: (errors) => {
            console.log(errors);
            event.target.value = ''; // Reset input
        }
    });
};


onMounted(() => {
  initFlowbite()
})

</script>
<template>
    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Search this product on Web.</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search This Product On Web" required="">
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <button class="bg-red-500 text-white px-2 py-1" v-if="selectedImage" @click="deleteProductImage(selectedImage.id)">Delete Image</button>
                     <!-- Hidden file input -->
                    <input 
                        ref="fileInput"
                        type="file" 
                        @change="handleFileSelect"
                        accept="image/*"
                        class="hidden"
                    />
                    <button type="button" @click="triggerFileInput" :disabled="router.processing" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add Image
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Brand</th>
                            <th class="px-4 py-3">Quantity</th>
                            <th class="px-4 py-3">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ product.title }}</th>
                            <td class="px-4 py-3">{{product.category.name}}</td>
                            <td class="px-4 py-3">{{ product.brand.name }}</td>
                            <td class="px-4 py-3">{{ product.quantity }}</td>
                            <td class="px-4 py-3">{{product.price}}</td>
                        </tr>
                        <tr class="border-b dark:border-gray-700">
                            <th scope="row" colspan="5" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div v-if="product.product_images.length > 0" class=" max-h-[400px] overflow-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div 
                                        v-for="(image, index) in product.product_images" 
                                        :key="image.id"
                                        @click="imageSelect(image, $event)"
                                        class="max-w-[300px] cursor-pointer rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
                                        :class="selectedImage?.id == image.id ? 'border-2 border-red-500' : ''" 
                                        >
                                        <img 
                                        :src="`/storage/${image.image}`"
                                        :alt="`Product image ${index + 1}`"
                                        class="w-full h-80 object-cover "
                                        />
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="5" class="px-4 py-3">{{product.description}}</td>
                        </tr>
                        <tr>
                            
                        </tr>
                        <tr>
                            
                        </tr>
                         <tr>
                            
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    </section>
</template>
<style scoped></style>