<script setup>
import draggable from 'vuedraggable'
import { ref, onMounted } from "vue";

const columns = ref({});

const fetchTasks = async () => {
  const res = await fetch("http://127.0.0.1:8000/api/tasks");
  const tasks = await res.json();

  const groups = {};

  tasks.forEach(task => {
    if (!groups[task.status]) {
      groups[task.status] = [];
    }
    groups[task.status].push(task);
  });

  columns.value = groups;
};

onMounted(() => {
  fetchTasks();
});

const formatStatus = (status) => {
  return status
    .replace(/_/g, " ")
    .replace(/\b\w/g, c => c.toUpperCase());
};
</script>

<template>
  <div class="kanban-container">
    <div 
      v-for="(tasks, status) in columns" 
      :key="status"
      class="kanban-column"
    >
      <h2>{{ formatStatus(status) }}</h2>

      <draggable
        v-model="columns[status]"
        group="tasks"
        item-key="id"
        class="kanban-list"
      >
        <template #item="{ element }">
          <div class="kanban-task">
            {{ element.title }}
          </div>
        </template>
      </draggable>
    </div>
  </div>
</template>

<style>
.kanban-container {
  display: flex;
  gap: 20px;
}

.kanban-column {
  padding: 10px;
  border: 1px solid black;
  width: 300px;
  max-height: 80vh;
  overflow-y: auto;
  background: #f8f8f8;
  border-radius: 6px;
}

.kanban-task {
  background: white;
  border: 1px solid #ccc;
  padding: 8px;
  margin-bottom: 10px;
}
</style>