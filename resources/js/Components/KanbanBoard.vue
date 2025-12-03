<script setup>
import draggable from 'vuedraggable'
import { ref, onMounted, computed } from "vue";

// PROPS
const props = defineProps({
  config: {
    type: Object,
    required: true,
    default: () => ({
      title: "Untitled Kanban",
      allowExport: true,
      allowCreate: true,
      colorTheme: "gray",
    }),
  },
});

// EMITS 
const emit = defineEmits(['task-created', 'task-moved']);

// STATE
const showModal = ref(false);
const columns = ref({});
const loading = ref(false);
let originalTasks = new Set();

const action = ref('Create');
const modalTitle = ref('New Task');

// COMPUTED
const title = computed(() => {
  return action.value + ' ' + modalTitle.value;
});

const boardTitle = computed(() => {
  return props.config.title;
});

// FUNCTIONS
const newTask = ref({
  'created_by': 22,
  'title': '',
  'description': '',
  'status': 'open',
});

const createTask = async () => {
  loading.value = true;

  await fetch("http://127.0.0.1:8000/api/tasks", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(newTask.value),
  });

  loading.value = false;
  showModal.value = false;
  
  await fetchTasks();
  refreshOriginalTasksSnapshot();

  // Emit to parent
  emit('task-created', newTask.value.title);

  // Reset form
  newTask.value = {
    'created_by': 22,
    'title': '',
    'description': '',
    'status': 'open',
  };
}

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

const refreshOriginalTasksSnapshot = () => {
  originalTasks = new Set(
    Object.values(columns.value).flat().map(t => t.id)
  );
};

const onTaskMoved = async (event, newStatus) => {
  if (!event.added) return;

  const movedTask = event.added.element;
  movedTask.status = newStatus;

  await fetch(`http://127.0.0.1:8000/api/tasks/${movedTask.id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(movedTask),
  });

  // Emit to parent
  emit('task-moved', movedTask.title);

  refreshOriginalTasksSnapshot();
};

const exportTasks = async () => {
  loading.value = true;

  const res = await fetch("http://127.0.0.1:8000/api/tasks/export", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
  });

  const data = await res.json();
  alert(data.message);

  loading.value = false;
}

const formatStatus = (status) => {
  return status
    .replace(/_/g, " ")
    .replace(/\b\w/g, c => c.toUpperCase());
};

// LIFECYCLE HOOKS
onMounted(async () => {
  await fetchTasks();
  refreshOriginalTasksSnapshot();
});
</script>

<template>
  <h1> {{ boardTitle }} </h1> <!-- props example -->
  <button class="create-btn" v-on:click="showModal = true">+ Create Task</button>
  <button class="export-btn" v-on:click="exportTasks">Export Tasks</button>
  <div class="kanban-container">
    <div 
      v-for="(tasks, status) in columns" 
      v-bind:key="status"
      class="kanban-column"
    >
      <h2>{{ formatStatus(status) }}</h2>

      <draggable
        v-model="columns[status]"
        group="tasks"
        item-key="id"
        class="kanban-list"
        v-on:change="onTaskMoved($event, status)"
      >
        <template #item="{ element }">
          <div class="kanban-task">
            {{ element.title }}
          </div>
        </template>
      </draggable>
    </div>
  </div>
  <div v-if="showModal" class="modal-overlay">
    <div class="modal-box">
      <h2> {{ title }}</h2> <!-- computed example -->

      <label>Title</label>
      <input v-model="newTask.title" type="text" />

      <label>Description</label>
      <textarea v-model="newTask.description"></textarea>

      <label>Status</label>
      <select v-model="newTask.status">
        <option value="open">Open</option>
        <option value="in_progress">In Progress</option>
        <option value="completed">Completed</option>
      </select>

      <div class="modal-actions">
        <button v-on:click="createTask">Create</button>
        <button v-on:click="showModal = false">Cancel</button>
      </div>
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

h1 {
  margin-bottom: 20px;
  font-size: 24px;
}

.create-btn, .modal-actions button {
  margin-bottom: 20px;
  padding: 5px 5px;
  background: #4f46e5;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.export-btn {
  margin-left: 10px;
  margin-bottom: 20px;
  padding: 5px 5px;
  background: #10b981;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-box {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
</style>