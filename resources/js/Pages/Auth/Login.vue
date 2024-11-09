<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"));
};
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2
                    class="mt-6 text-center text-3xl font-extrabold text-gray-900"
                >
                    Вход в систему
                </h2>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Email"
                        />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Пароль</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Пароль"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label
                            for="remember"
                            class="ml-2 block text-sm text-gray-900"
                        >
                            Запомнить меня
                        </label>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Войти
                    </button>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">
                        Тестовые пользователи, клик для входа:
                    </h3>
                    <div class="space-y-2">
                        <button
                            type="button"
                            @click="
                                () => {
                                    form.email = 'tech@example.com';
                                    form.password = 'password';
                                    submit();
                                }
                            "
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                        >
                            <div class="font-medium">Иван Технический</div>
                            <div class="text-xs text-gray-500">
                                Email: tech@example.com<br />
                                Пароль: password<br />
                                Должность: Разработчик
                            </div>
                        </button>

                        <button
                            type="button"
                            @click="
                                () => {
                                    form.email = 'finance@example.com';
                                    form.password = 'password';
                                    submit();
                                }
                            "
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                        >
                            <div class="font-medium">Мария Финансист</div>
                            <div class="text-xs text-gray-500">
                                Email: finance@example.com<br />
                                Пароль: password<br />
                                Должность: Бухгалтер
                            </div>
                        </button>

                        <button
                            type="button"
                            @click="
                                () => {
                                    form.email = 'sales@example.com';
                                    form.password = 'password';
                                    submit();
                                }
                            "
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                        >
                            <div class="font-medium">Петр Продажник</div>
                            <div class="text-xs text-gray-500">
                                Email: sales@example.com<br />
                                Пароль: password<br />
                                Должность: Менеджер по продажам
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
