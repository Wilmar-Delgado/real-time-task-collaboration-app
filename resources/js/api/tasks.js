export function getTasks() {
    return fetch('/api/tasks').then(r => r.json())
}

export function createTask(data) {
    return fetch('/api/tasks', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    })
}

export function updateTask(id, data) {
    return fetch(`/api/tasks/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    })
}

export function deleteTaskById(id) {
    return fetch(`/api/tasks/${id}`, { method: 'DELETE' })
}

export function exportTaskList() {
    return fetch('/api/tasks/export', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
    }).then(r => r.json());
}