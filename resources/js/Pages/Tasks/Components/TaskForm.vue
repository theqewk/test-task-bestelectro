<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    users: Array,
    departments: Array,
});

const emit = defineEmits(["close"]);

const form = useForm({
    title: "",
    description: "",
    priority: "",
    assignee_id: "",
    department_id: "",
    watchers: [],
});

const selectedWatchers = ref([]);

const priorities = [
    { value: "low", label: "Низкий" },
    { value: "normal", label: "Обычный" },
    { value: "high", label: "Высокий" },
    { value: "blocking", label: "Блокирующий" },
];

const addWatcher = (type, id) => {
    if (!id) return;

    const existingWatcher = selectedWatchers.value.find(
        (w) => w.type === type && w.id === id
    );
    if (!existingWatcher) {
        selectedWatchers.value.push({ type, id });
        form.watchers = selectedWatchers.value;
    }
};

const removeWatcher = (type, id) => {
    selectedWatchers.value = selectedWatchers.value.filter(
        (w) => !(w.type === type && w.id === id)
    );
    form.watchers = selectedWatchers.value;
};

const submit = () => {
    form.post(route("tasks.store"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            emit("close");
        },
    });
};
</script>

<template>
    <div
        class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center"
    >
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
            <div
                v-if="form.errors.error"
                class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg"
            >
                {{ form.errors.error }}
            </div>

            <form @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Название <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.title }"
                        />
                        <div
                            v-if="form.errors.title"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.title }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Описание <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{
                                'border-red-500': form.errors.description,
                            }"
                        ></textarea>
                        <div
                            v-if="form.errors.description"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Приоритет <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.priority"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.priority }"
                        >
                            <option value="">Выберите приоритет</option>
                            <option
                                v-for="priority in priorities"
                                :key="priority.value"
                                :value="priority.value"
                            >
                                {{ priority.label }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.priority"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.priority }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Исполнитель
                        </label>
                        <select
                            v-model="form.assignee_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.executor }"
                        >
                            <option value="">Выберите исполнителя</option>
                            <option
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }} ({{ user.position }})
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Отдел
                        </label>
                        <select
                            v-model="form.department_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.executor }"
                        >
                            <option value="">Выберите отдел</option>
                            <option
                                v-for="department in departments"
                                :key="department.id"
                                :value="department.id"
                            >
                                {{ department.name }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.executor"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.executor }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Наблюдатели
                        </h3>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Сотрудники</label
                                >
                                <select
                                    @change="
                                        addWatcher('user', $event.target.value)
                                    "
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">
                                        Выберите сотрудника
                                    </option>
                                    <option
                                        v-for="user in users"
                                        :key="user.id"
                                        :value="user.id"
                                    >
                                        {{ user.name }} ({{ user.position }})
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Отделы</label
                                >
                                <select
                                    @change="
                                        addWatcher(
                                            'department',
                                            $event.target.value
                                        )
                                    "
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Выберите отдел</option>
                                    <option
                                        v-for="department in departments"
                                        :key="department.id"
                                        :value="department.id"
                                    >
                                        {{ department.name }}
                                    </option>
                                </select>
                            </div>

                            <div
                                v-if="selectedWatchers.length > 0"
                                class="mt-4"
                            >
                                <h4 class="text-sm font-medium text-gray-700">
                                    Выбранные наблюдатели:
                                </h4>
                                <ul class="mt-2 divide-y divide-gray-200">
                                    <li
                                        v-for="watcher in selectedWatchers"
                                        :key="`${watcher.type}-${watcher.id}`"
                                        class="py-2 flex justify-between items-center"
                                    >
                                        <span>
                                            {{
                                                watcher.type === "user"
                                                    ? users.find(
                                                          (u) =>
                                                              u.id ===
                                                              Number(watcher.id)
                                                      )?.name
                                                    : departments.find(
                                                          (d) =>
                                                              d.id ===
                                                              Number(watcher.id)
                                                      )?.name
                                            }}
                                        </span>
                                        <button
                                            type="button"
                                            @click="
                                                removeWatcher(
                                                    watcher.type,
                                                    watcher.id
                                                )
                                            "
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            Удалить
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button
                        type="button"
                        @click="emit('close')"
                        :disabled="form.processing"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Отмена
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <span v-if="form.processing" class="mr-2">
                            <svg
                                class="animate-spin h-4 w-4 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                        </span>
                        {{ form.processing ? "Создание..." : "Создать" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
