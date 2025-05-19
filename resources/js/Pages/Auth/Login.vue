<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";

const form = useForm({
    email: null,
    password: null,
    remember: null,
});

const submit = () => {
    form.post(route("login"), {
        onError: () => form.reset('password')
    });
};
</script>


<template>
    <Head title="Login" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-5">
            Masuk
        </p>

        <form @submit.prevent="submit">
            <TextInput name="Alamat Email" id="email" type="email" v-model="form.email" :message="form.errors.email" />
            <TextInput name="Password" id="password" type="password" v-model="form.password"
                :message="form.errors.password" />

            <button type="submit"
                class="mt-3 mb-2 text-white bg-teal-600 hover:opacity-50 focus:ring-teal-600 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-teal-400 dark:hover:opacity-50 dark:focus:ring-teal-400 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing">
                Masuk
            </button>

            <div class="flex items-center justify-between">
                <p class="text-sm">Tidak punya Akun? <Link :href="route('register')"
                        class="text-teal-600 dark:text-teal-400 font-semibold">Registrasi</Link></p>

                <div class="flex items-center gap-2">
                    <input id="remember" type="checkbox" v-model="form.remember"
                        class="w-4 h-4 text-teal-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-teal-600 dark:focus:ring-teal-400 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label class="text-sm" for="remember">Ingat Saya</label>
                </div>

            </div>

        </form>
    </div>
</template>
