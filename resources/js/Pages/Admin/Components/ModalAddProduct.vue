<script setup>
    import { ref, inject} from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    
    
    const categories = inject('categories');
    const brands = inject('brands');

    const props = defineProps({
        product: {
            type: Object,
            default: null
        },
    });

    
    const form = useForm({
        title: props?.product?.title || '',
        category_id: props?.product?.category_id || '',
        brand_id: props?.product?.brand_id || '',
        price: props?.product?.price || '',
        quantity: props?.product?.quantity || '',
        description: props?.product?.description || '',
        published: false,
        inStock: true,
        images: [],
    })

    const previews = ref([]);

    const centerDialogVisible = defineModel('centerDialogVisible');

    const emit = defineEmits(['formSuccess']);

    const handleFiles = (e) => {
        const files = Array.from(e.target.files)

        files.forEach((file) => {
            form.images.push(file)
            previews.value.push(URL.createObjectURL(file))
        })
    }

    const removeImage = (index) => {
        form.images.splice(index, 1)
        previews.value.splice(index, 1)
    }

    const resetForm = () => {
        centerDialogVisible.value = false;
        previews.value = [];
        form.reset();   
    };

    const submit = () => {
        form.post('/admin/products/store', {
            forceFormData: true,
            onSuccess: () => {
            form.reset()         // reset form after success
            previews.value = [];
            centerDialogVisible.value = false;
            router.reload({ only: ['products'] })  // only re-fetches products prop
            emit('formSuccess', true);
            },
            onError: (errors) => {
            console.log(errors)  // handle errors
            }
        });

        //onerror
        //show the errors on form
    } 

</script>
<template>
    <el-dialog
    v-model="centerDialogVisible"
    :title=" product ? 'Edit Product' : 'Add New Product'"
    width="500"
    align-center
  >
    <!-- Form Element -->
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl">
            <form action="#">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                         <span v-if="form.errors.title">{{ form.errors.title }}</span>
                        <input v-model="form.title" type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                    </div>
                    <div>
                        <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">brand</label>
                        <span v-if="form.errors.brand_id">{{ form.errors.brand_id }}</span>
                        <select v-model="form.brand_id" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option disabled selected value="">Select brand</option>
                            <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{brand.name}}</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                        <span v-if="form.errors.price">{{ form.errors.price }}</span>
                        <input v-model="form.price" type="text" name="price" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$2999" required="">
                    </div>
                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <span v-if="form.errors.category_id">{{ form.errors.category_id }}</span>
                        <select v-model="form.category_id" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option disabled selected value="">Select category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                        </select>
                    </div>
                    <div>
                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item Quantity (Pcs)</label>
                         <span v-if="form.errors.quantity">{{ form.errors.quantity }}</span>
                        <input type="number" v-model="form.quantity" name="quantity" id="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="12" required="">
                    </div> 
                    <!-- inserting images option -->
                    <div class="sm:col-span-2">
                          <input id="imageUpload" type="file" accept="image/*" multiple @change="handleFiles" class="hidden"/>
                          <!-- Custom styled button -->
                        <label for="imageUpload" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-500 text-gray-300 hover:border-white hover:text-white transition-colors duration-200">
                            <span>＋ Add Images</span>
                        </label>
                        <span v-if="form.errors.images" class="text-red-500">{{ form.errors.images }}</span>

                        <!-- Progress bar -->
                        <div v-if="form.progress">
                        <el-progress :percentage="form.progress.percentage" striped-flow />
                        <span>{{ form.progress.percentage }}%</span>
                        </div>

                        <!-- Image previews -->
                        <div v-if="previews.length" class="mt-2 flex gap-2 flex-wrap">
                        <div v-for="(src, index) in previews" :key="index" class="relative">
                            <img :src="src" class="w-16 h-16 object-cover rounded" />
                            <button @click="removeImage(index)" class="absolute top-0 right-0 text-red-500">✕</button>
                        </div>
                        </div>
                    </div>
                    <!-- end of insert images option -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <span v-if="form.errors.description">{{ form.errors.description }}</span>
                        <textarea id="description" v-model="form.description" rows="5" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Your description here"></textarea>
                    </div>
                </div>
                <!-- <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                    Add product
                </button> -->
            </form>
        </div>
    </section>
    <!-- end of form -->
    <template #footer>
      <div class="dialog-footer">
        <el-button @click="resetForm">Cancel</el-button>
        <el-button type="primary" @click="submit" :disabled="form.processing">
          Confirm
        </el-button>
      </div>
    </template>
  </el-dialog>
</template>
<style scoped></style>