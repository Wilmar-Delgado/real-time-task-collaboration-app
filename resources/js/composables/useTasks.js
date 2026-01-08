import { ref } from 'vue'
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

    async function createTask(task) {
        await taskApi.createTask(task)
        await fetchTasks()
    }

    async function updateTask(task) {
        await taskApi.updateTask(task.id, task)
        await fetchTasks()
    }

    async function deleteTask(task) {
        await taskApi.deleteTaskById(task.id)
        await fetchTasks()
    }

    async function moveTask(task, newStatus) {
        task.status = newStatus
        await taskApi.updateTask(task.id, task)
    }

    async function exportTasks() {
        return taskApi.exportTaskList()
    }

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
