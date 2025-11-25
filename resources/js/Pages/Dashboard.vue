<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import KanbanBoard from '@/Components/KanbanBoard.vue'
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <SidebarLayout>
        <template #title>
            Dashboard
        </template>

        <!-- Full-width content, no padding, no card -->
        <div class="p-6">
            <KanbanBoard />
        </div>
    </SidebarLayout>
</template>
