import { ref, onMounted, onUnmounted } from 'vue'
import * as taskApi from '@/api/tasks'

export function useTasks() {
    const columns = ref({})

    async function fetchTasks() {
        const tasks = await taskApi.getTasks()
        const groups = {}

        tasks.forEach(task => {
            if (!groups[task.status]) {
                groups[task.status] = []
            }
            groups[task.status].push(task)
        })

        columns.value = groups
    }

    function addTask(task) {
        if (!columns.value[task.status]) {
            columns.value[task.status] = []
        }
        columns.value[task.status].push(task)
    }

    function updateTaskInState(task) {
        Object.values(columns.value).forEach(list => {
            const index = list.findIndex(t => t.id === task.id)
            if (index !== -1) list.splice(index, 1)
        })
        addTask(task)
    }

    function removeTaskFromState(task) {
        Object.values(columns.value).forEach(list => {
            const index = list.findIndex(t => t.id === task.id)
            if (index !== -1) list.splice(index, 1)
        })
    }

    function listenForTaskUpdates() {
        console.log('Subscribing to tasks channel')
        window.Echo
            .channel('tasks')
            .listen('.TaskUpdated', (e) => {
                console.log('EVENT RECEIVED:', e)

                if (e.action === 'created') addTask(e.task)
                if (e.action === 'updated') updateTaskInState(e.task)
                if (e.action === 'deleted') removeTaskFromState(e.task)
            })
    }

    async function createTask(task) {
        await taskApi.createTask(task)
    }

    async function updateTask(task) {
        await taskApi.updateTask(task.id, task)
    }

    async function deleteTask(task) {
        await taskApi.deleteTaskById(task.id)
    }

    async function moveTask(task, newStatus) {
        task.status = newStatus
        await taskApi.updateTask(task.id, task)
    }

    async function exportTasks() {
        return taskApi.exportTaskList()
    }

    onMounted(listenForTaskUpdates)
    onUnmounted(() => window.Echo.leave('tasks'))

    return {
        columns,
        fetchTasks,
        createTask,
        updateTask,
        deleteTask,
        moveTask,
        exportTasks,
    }
}
