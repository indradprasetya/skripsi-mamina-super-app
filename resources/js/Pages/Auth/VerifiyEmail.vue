<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const processing = ref(false)
const message = ref(usePage().props.flash?.message || '')

function resend() {
    processing.value = true
    router.post(route('verification.send'), {}, {
        onFinish: () => processing.value = false,
        onSuccess: () => message.value = 'Link verifikasi telah dikirim ulang ke email Anda.'
    })
}
</script>

<template>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
        <h1 class="text-xl font-bold mb-4 text-teal-600">Verifikasi Email Anda</h1>
        <p class="mb-4">Kami telah mengirimkan link verifikasi ke email Anda. Silakan cek email dan klik link tersebut
            untuk mengaktifkan akun.</p>
        <form @submit.prevent="resend">
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700"
                :disabled="processing">
                Kirim Ulang Email Verifikasi
            </button>
        </form>
        <p v-if="message" class="text-green-600 mt-3">{{ message }}</p>
    </div>
</template>