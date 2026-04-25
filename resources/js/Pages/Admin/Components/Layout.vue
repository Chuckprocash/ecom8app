<script setup>
  import Navbar from '@/Pages/Admin/Components/Navbar.vue';
  import Sidebar from '@/Pages/Admin/Components/Sidebar.vue';
  import Footer from '@/Pages/Admin/Components/Footer.vue';
  import { Head } from '@inertiajs/vue3';
</script>

<template>
<Head title="Dashboard" />
<div class="antialiased bg-gray-50 dark:bg-gray-900">
    <!-- Navbar -->
    <Navbar/>
    <!-- Sidebar -->
    <Sidebar/>
    
    <!-- Main -->
    <main class="p-4 md:ml-64 min-h-screen pt-20">
       <slot></slot>
    </main>
  </div>
</template>
