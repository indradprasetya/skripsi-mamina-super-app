<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
    form.put(route('password.update'), {
        onSuccess: () => {
            form.reset();
            showPassword.value = false;
            showNewPassword.value = false;
            showConfirmPassword.value = false;
        },
        onError: (errors) => {
            console.log('Error:', errors);
        },
        preserveScroll: true,
    });
};
</script>

<template>

    <Head title="Ganti Password" />

    <div class="py-6">
        <div>
            <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-6">Ganti Password</p>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Current Password -->
                    <div>
                        <TextInput id="current_password" type="password" v-model="form.current_password"
                            name="Password Saat Ini" :show-password="showPassword"
                            @toggle-password="showPassword = !showPassword" :message="form.errors.current_password"
                            required />
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>

                    <!-- New Password -->
                    <div>
                        <TextInput id="password" type="password" v-model="form.password" name="Password Baru"
                            :show-password="showNewPassword" @toggle-password="showNewPassword = !showNewPassword"
                            :message="form.errors.password" required />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <TextInput id="password_confirmation" type="password" v-model="form.password_confirmation"
                            name="Konfirmasi Password Baru" :show-password="showConfirmPassword"
                            @toggle-password="showConfirmPassword = !showConfirmPassword"
                            :message="form.errors.password_confirmation" required />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="text-white bg-teal-600 hover:opacity-50 focus:ring-teal-600 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-teal-400 dark:hover:opacity-50 dark:focus:ring-teal-400 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="form.processing">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>