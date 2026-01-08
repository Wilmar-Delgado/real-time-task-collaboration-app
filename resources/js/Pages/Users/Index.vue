<script setup>
import SidebarLayout from '@/Layouts/SidebarLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// ─────────────────────────────
// PROPS & EMITS
// ─────────────────────────────
// const emit = defineEmits(['user-action'])

// ─────────────────────────────
// STATE
// ─────────────────────────────
const showModal = ref(false)
const action = ref('Create')
const modalTitle = ref('New User')
const users = ref([]);
const newUser = ref({
    id: null,
    name: '',
    email: '',
    password: '',
})

// ─────────────────────────────
// ACTIONS
// ─────────────────────────────
const saveUser = async () => {
    if (action.value === 'Create') {
        await createUser(newUser.value)
        alert(`User "${newUser.value.name}" was created.`);
    } else {
        await updateUser(newUser.value)
        alert(`User "${newUser.value.name}" was updated.`);
    }

    // emit('user-action', {
    //     title: newUser.value.name,
    //     type: action.value === 'Create' ? 'created' : 'updated',
    // })

    resetForm()
    showModal.value = false
}

const createUser = async (user) => {
    await fetch('/api/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(user),
    });

    // Refresh user list
    const res = await fetch('/api/users')
    users.value = await res.json()
}

const updateUser = async (user) => {
    await fetch(`/api/users/${user.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(user),
    });

    // Refresh user list
    const res = await fetch('/api/users')
    users.value = await res.json()
}

const editUser = (user) => {
    action.value = 'Update'
    modalTitle.value = 'Edit User'

    newUser.value = {
        id: user.id,
        name: user.name,
        email: user.email,
        password: '', // always blank for security
    }
    
    showModal.value = true
}

const deleteUser = async (user) => {
    if (!confirm(`Delete "${user.name}"?`)) return

    await fetch(`/api/users/${user.id}`, {
        method: 'DELETE',
    });

    alert(`User "${user.name}" was deleted.`)

    // Refresh user list
    const res = await fetch('/api/users')
    users.value = await res.json()
}

// ─────────────────────────────
// UTIL
// ─────────────────────────────
const resetForm = () => {
    newUser.value = {
        id: null,
        name: '',
        email: '',
        password: '',
    }

    action.value = 'Create'
    modalTitle.value = 'New User'
}

// ─────────────────────────────
// LIFECYCLE
// ─────────────────────────────
onMounted(async () => {
    const res = await fetch('/api/users')
    users.value = await res.json()
});
</script>

<template>
    <Head title="Users" />
    <SidebarLayout>
        <template #title>
            Users
        </template>

        <div class="p-6">
            <button
                class="create-btn"
                v-on:click="resetForm(); showModal = true"
            >
                + Create User
            </button>

            <button
                class="export-btn"
                v-on:click="handleExport"
            >
                Export Users
            </button>
            <div class="table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td><button class="edit-btn" v-on:click="editUser(user)">✏️</button>
                            <button class="delete-btn" v-on:click="deleteUser(user)">🗑️</button>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- MODAL -->
            <div v-if="showModal" class="modal-overlay">
                <div class="modal-box">
                <h2>{{ modalTitle }}</h2>

                <label>Name</label>
                <input v-model="newUser.name" type="text" />

                <label>Email</label>
                <input v-model="newUser.email" type="email" />

                <label>Password</label>
                <input
                v-model="newUser.password"
                type="password"
                :placeholder="action === 'Update'
                    ? 'Leave blank to keep current password'
                    : 'Enter password'"
                />

                <div class="modal-actions">
                    <button v-on:click="saveUser">{{ action }}</button>
                    <button v-on:click="showModal = false; resetForm()">Cancel</button>
                </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>

<style>
.table-wrapper {
    border-radius: 12px;
    overflow: hidden; /* THIS is the key */
    border: 1px solid #e5e7eb;
    background: white;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th,
.users-table td {
    padding: 10px;
    border: 1px solid black;
    text-align: left;
}

.users-table th {
    background: #f8f8f8;
    font-weight: 600;
}

.users-table tbody tr:hover {
    background-color: #f3f4f6;
}

.users-table td:last-child {
    white-space: nowrap;
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