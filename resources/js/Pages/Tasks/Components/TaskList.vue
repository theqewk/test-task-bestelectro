<script setup>
import { useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    tasks: Array,
    users: Array,
    departments: Array,
});

const statusLabels = {
    new: "Новая",
    in_progress: "В процессе",
    completed: "Выполнено",
    rejected: "Отклонено",
};

const priorityLabels = {
    low: "Низкий",
    normal: "Обычный",
    high: "Высокий",
    blocking: "Блокирующий",
};

const updateStatus = (task, status) => {
    useForm({
        status: status,
        assignee_id: task.assignee_id,
    }).put(route("tasks.update", task.id));
};

const canUpdateTask = (task) => {
    const user = usePage().props.auth.user;

    return (
        task.creator_id === user.id ||
        task.assignee_id === user.id ||
        task.department_id === user.department_id
    );
};
</script>

<template>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <ul class="divide-y divide-gray-200">
            <li v-for="task in tasks" :key="task.id" class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ task.title }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ task.description }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span
                            :class="{
                                'px-2 py-1 text-xs font-medium rounded-full': true,
                                'bg-red-100 text-red-800':
                                    task.priority === 'blocking',
                                'bg-yellow-100 text-yellow-800':
                                    task.priority === 'high',
                                'bg-blue-100 text-blue-800':
                                    task.priority === 'normal',
                                'bg-gray-100 text-gray-800':
                                    task.priority === 'low',
                            }"
                        >
                            {{ priorityLabels[task.priority] }}
                        </span>
                        <select
                            v-if="canUpdateTask(task)"
                            v-model="task.status"
                            @change="updateStatus(task, $event.target.value)"
                            class="rounded-md border-gray-300 text-sm"
                        >
                            <option
                                v-for="(label, value) in statusLabels"
                                :key="value"
                                :value="value"
                            >
                                {{ label }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-500">
                    <p>Создал: {{ task.creator.name }}</p>
                    <p>
                        Исполнитель:
                        {{
                            task.assignee?.name ||
                            `Отдел: ${task.department.name}`
                        }}
                    </p>

                    <!-- Отображение наблюдателей -->
                    <div
                        v-if="task.watchers && task.watchers.length > 0"
                        class="mt-2"
                    >
                        <p class="font-medium">Наблюдатели:</p>
                        <ul class="ml-4 list-disc">
                            <li
                                v-for="watcher in task.watchers"
                                :key="`${watcher.type}-${watcher.id}`"
                            >
                                {{ watcher.name }} ({{
                                    watcher.type === "user"
                                        ? "Сотрудник"
                                        : "Отдел"
                                }})
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
