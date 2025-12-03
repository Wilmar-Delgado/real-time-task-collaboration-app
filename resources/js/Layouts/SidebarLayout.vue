<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
    <div class="layout-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo">
                <Link :href="route('dashboard')">
                    Real-Time Task Collaboration App
                </Link>
            </div>

            <nav class="nav-links">
                <Link
                    :href="route('dashboard')"
                    class="nav-link"
                    :class="{ active: $page.url.startsWith('/dashboard') }"
                >
                    Dashboard
                </Link>

                <Link
                    :href="route('profile.edit')"
                    class="nav-link"
                    :class="{ active: $page.url.startsWith('/profile') }"
                >
                    Profile
                </Link>
            </nav>

            <div class="logout-container">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="logout-btn"
                >
                    Logout
                </Link>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="content-wrapper">

            <!-- TOPBAR -->
            <header class="topbar">
                <h1 class="topbar-title">
                    <slot name="title" />
                </h1>

                <p class="topbar-user">{{ user.name }}</p>
            </header>

            <!-- PAGE CONTENT -->
            <main class="content-area">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
/* ===== Layout Container ===== */
.layout-container {
    display: flex;
    height: 100vh;
    background: #f3f4f6;
    color: #374151;
}

/* ===== Sidebar ===== */
.sidebar {
    width: 260px;
    background: #ffffff;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
}

/* Logo */
.logo {
    height: 64px;
    padding: 0 24px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #e5e7eb;
    font-size: 20px;
    font-weight: bold;
}

/* Navigation */
.nav-links {
    flex: 1;
}

.nav-link {
    display: block;
    padding: 12px 24px;
    text-decoration: none;
    color: #374151;
    transition: background 0.2s;
}

.nav-link:hover {
    background: #f3f4f6;
}

.nav-link.active {
    background: #e5e7eb;
    font-weight: 600;
}

/* Logout section */
.logout-container {
    padding: 16px;
    border-top: 1px solid #e5e7eb;
}

.logout-btn {
    width: 100%;
    padding: 10px 16px;
    border-radius: 4px;
    background: #fef2f2;
    color: #dc2626;
    text-align: left;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.logout-btn:hover {
    background: #fee2e2;
}

/* ===== Main Content ===== */
.content-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Topbar */
.topbar {
    height: 64px;
    padding: 0 24px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.topbar-title {
    font-size: 20px;
    font-weight: 600;
}

.topbar-user {
    color: #6b7280;
}
</style>