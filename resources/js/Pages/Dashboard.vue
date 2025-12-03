<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import KanbanBoard from '@/Components/KanbanBoard.vue'
import { Head } from '@inertiajs/vue3';

const kanbanConfig = {
  title: "Development Workflow",
  allowExport: true,
  allowCreate: true,
  colorTheme: "purple",
};

const handleTaskCreated = (taskTitle) => {
  alert('New task created:' + taskTitle);
};

const handleTaskMoved = (taskTitle) => {
  alert('Task ' + taskTitle + ' moved successfully!');
};
</script>

<template>
    <Head title="Dashboard" />

    <SidebarLayout>
        <template #title>
            Dashboard
        </template>

        <!-- Full-width content, no padding, no card -->
        <div class="p-6">
            <KanbanBoard v-bind:config="kanbanConfig" @task-created="handleTaskCreated" @task-moved="handleTaskMoved"/>
        </div>
    </SidebarLayout>
</template>
