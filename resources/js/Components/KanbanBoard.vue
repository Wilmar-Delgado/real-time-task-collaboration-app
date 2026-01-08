<script setup>
import draggable from 'vuedraggable'
import { ref, computed, onMounted } from 'vue'
import { useTasks } from '@/composables/useTasks'
import { formatStatus } from '@/utils/format'

// ─────────────────────────────
// PROPS & EMITS
// ─────────────────────────────
const props = defineProps({
  config: {
    type: Object,
    required: true,
    default: () => ({
      title: 'Untitled Kanban',
      allowExport: true,
      allowCreate: true,
    }),
  },
})

const emit = defineEmits(['task-action', 'task-moved'])

// ─────────────────────────────
// COMPOSABLE (TASK LOGIC)
// ─────────────────────────────
const {
  columns,
  fetchTasks,
  createTask,
  updateTask,
  deleteTask,
  moveTask,
  exportTasks,
} = useTasks()

// ─────────────────────────────
// STATE
// ─────────────────────────────
const showModal = ref(false)
const action = ref('Create')
const modalTitle = ref('New Task')
const loading = ref(false)

const newTask = ref({
  created_by: 4,
  title: '',
  description: '',
  status: 'open',
})

// ─────────────────────────────
// COMPUTED
// ─────────────────────────────
const boardTitle = computed(() => props.config.title)

// ─────────────────────────────
// ACTIONS
// ─────────────────────────────
const saveTask = async () => {
  if (action.value === 'Create') {
    await createTask(newTask.value)
  } else {
    await updateTask(newTask.value)
  }

  emit('task-action', {
    title: newTask.value.title,
    type: action.value === 'Create' ? 'created' : 'updated',
  })

  resetForm()
  showModal.value = false
}

const editTask = (task) => {
  action.value = 'Update'
  modalTitle.value = 'Edit Task'

  newTask.value = { ...task }
  showModal.value = true
}

const handleDelete = async (task) => {
  if (!confirm(`Delete "${task.title}"?`)) return

  await deleteTask(task)

  emit('task-action', {
    title: task.title,
    type: 'deleted',
  })
}

const onTaskMoved = async (event, newStatus) => {
  if (!event.added) return

  const movedTask = event.added.element
  await moveTask(movedTask, newStatus)

  emit('task-moved', movedTask.title)
}

const handleExport = async () => {
  const res = await exportTasks()
  alert(res.message)
}

// ─────────────────────────────
// UTIL
// ─────────────────────────────
const resetForm = () => {
  newTask.value = {
    created_by: 4,
    title: '',
    description: '',
    status: 'open',
  }

  action.value = 'Create'
  modalTitle.value = 'New Task'
}

// ─────────────────────────────
// LIFECYCLE
// ─────────────────────────────
onMounted(async () => {
  loading.value = true
  await fetchTasks()
  loading.value = false
})
</script>

<template>
  <!-- <h1>{{ boardTitle }}</h1> -->

  <button
    v-if="config.allowCreate"
    class="create-btn"
    v-on:click="resetForm(); showModal = true"
  >
    + Create Task
  </button>

  <button
    v-if="config.allowExport"
    class="export-btn"
    v-on:click="handleExport"
  >
    Export Tasks
  </button>

  <div v-if="loading" class="kanban-loader">
    <div class="spinner"></div>
    <p>Loading tasks...</p>
  </div>

  <div v-else class="kanban-container">
    <div
      v-for="(tasks, status) in columns" :key="status"
      class="kanban-column"
    >
      <h2>{{ formatStatus(status) }}</h2>

      <draggable
        v-model="columns[status]"
        group="tasks"
        item-key="id"
        class="kanban-list"
        @change="onTaskMoved($event, status)"
      >
        <template #item="{ element }">
          <div class="kanban-task">
            {{ element.title }}

            <button class="edit-btn" v-on:click="editTask(element)">✏️</button>
            <button class="delete-btn" v-on:click="handleDelete(element)">🗑️</button>
          </div>
        </template>
      </draggable>
    </div>
  </div>

  <!-- MODAL -->
  <div v-if="showModal" class="modal-overlay">
    <div class="modal-box">
      <h2>{{ modalTitle }}</h2>

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
        <button v-on:click="saveTask">{{ action }}</button>
        <button v-on:click="showModal = false; resetForm()">Cancel</button>
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
  max-height: 60vh;
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

.kanban-loader {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 300px;
  gap: 10px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e5e7eb;
  border-top-color: #4f46e5;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

h1 {
  margin-bottom: 10px;
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

.edit-btn, .delete-btn {
  font-size: 10px;
  float: right;
  background: transparent;
  border: none;
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