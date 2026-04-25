<script setup>
    import Navbar from './Components/Navbar.vue';
    import Footer from './Components/Footer.vue';
    import { watch } from 'vue'
    import { usePage } from '@inertiajs/vue3'
    import Swal from 'sweetalert2'

    const page = usePage()


    watch(() => page.props.flash.error, (message) => {
        if (message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            })
        }
    })

    watch(() => page.props.flash.success, (message) => {
        if (message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            })
        }
    })
</script>
<template>
    <Navbar/>
    <main class="min-h-screen">
        <slot></slot>
    </main>
    <Footer/>
</template>
<style scoped></style>