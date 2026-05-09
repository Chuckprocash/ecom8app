<script setup>
import AdminLayout from './Layout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const modalForm = ref(0);

const boxOpen = () => {
    modalForm.value = 1; 
};
const props = defineProps({
    categories : Array,
});

const submitForm = () => {
    form.post(route('category.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            modalForm.value = false
        }
    })
}

const cancelForm = () => {
    form.reset()
    form.clearErrors()
    modalForm.value = false
}
</script>
<template>
    <AdminLayout>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 w-auto">Category name</th>
                        <th class="w-32 px-4 py-3 cursor-pointer hover:bg-gray-500" @click="boxOpen">Add Category</th>
                    </tr>
                </thead>
                <tbody>
                   <tr v-if="modalForm" class="border-b border-slate-700">
                        <td class="px-4 py-3 w-auto">
                            <input 
                                type="text" 
                                placeholder="Category name" 
                                v-model="form.name"
                                class="w-full bg-transparent border-none text-white placeholder-slate-500 focus:outline-none text-sm"
                                :class="{ 'text-red-400': form.errors.name }"
                            />
                            <p v-if="form.errors.name" class="text-xs text-red-400 mt-0.5">
                                {{ form.errors.name }}
                            </p>
                        </td>
                        <td class="px-4 py-3 w-auto">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="submitForm"
                                    class="text-green-400 hover:text-green-300 text-sm font-medium transition-colors disabled:opacity-50"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">Saving...</span>
                                    <span v-else>Save</span>
                                </button>
                                <span class="text-slate-600">|</span>
                                <button
                                    type="button"
                                    @click="cancelForm"
                                    class="text-slate-400 hover:text-slate-300 text-sm transition-colors"
                                    :disabled="form.processing"
                                >
                                    Cancel
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="category in categories" class="border-b border-b-slate-700">
                        <td class="px-4 py-3 w-auto">{{ category.name }}</td>
                        <td class="w-32 px-4 py-3">edit/delete</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
<style scoped></style>