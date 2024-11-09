<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import TaskForm from './Components/TaskForm.vue'
import TaskList from './Components/TaskList.vue'

const props = defineProps({
    tasks: Array,
    users: Array,
    departments: Array,
    auth: Object
})

const showCreateForm = ref(false)
</script>

<template>
    <Head title="Задачи" />

    <div class="min-h-screen bg-gray-100">
        <!-- Навигационная панель -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-lg font-semibold text-gray-800">
                            Таск-трекер
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 mr-4">
                            {{ auth.user.name }} ({{ auth.user.position }})
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ departments.find(d => d.id === auth.user.department_id)?.name }}
                        </span>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="text-sm text-gray-500 hover:text-gray-700 ml-4"
                        >
                            Выйти
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Основной контент -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Заголовок и кнопка создания -->
            <div class="px-4 sm:px-0 mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">Задачи</h1>
                    <button
                        @click="showCreateForm = true"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Создать задачу
                    </button>
                </div>
            </div>

            <!-- Статистика -->
            <div class="px-4 sm:px-0 mb-6">
                <dl class="grid grid-cols-1 gap-5 sm:grid-cols-4">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Всего задач
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ tasks.length }}
                            </dd>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Новые
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ tasks.filter(t => t.status === 'new').length }}
                            </dd>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                В процессе
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ tasks.filter(t => t.status === 'in_progress').length }}
                            </dd>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Завершено
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ tasks.filter(t => t.status === 'completed').length }}
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>

            <!-- Список задач -->
            <div class="px-4 sm:px-0">
                <TaskList
                    :tasks="tasks"
                    :users="users"
                    :departments="departments"
                />
            </div>

            <!-- Модальное окно создания задачи -->
            <TaskForm
                v-if="showCreateForm"
                :users="users"
                :departments="departments"
                @close="showCreateForm = false"
            />
        </main>
    </div>
</template>
