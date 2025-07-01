<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const testNotification = async () => {
    try {
        const permission = await Notification.requestPermission();
        if (permission === 'granted') {
            new Notification('Test Notifikasi', {
                body: 'Ini adalah percobaan notifikasi dari Mamina Super App'
            });
        }
    } catch (error) {
        console.error('Error showing notification:', error);
    }
};

onMounted(() => {
    const themeToggle = document.getElementById('theme-toggle')

    if (!themeToggle) return

    // Set initial state
    if (
        localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        themeToggle.checked = true
    }

    // Toggle logic
    themeToggle.addEventListener('change', () => {
        if (themeToggle.checked) {
            document.documentElement.classList.add('dark')
            localStorage.setItem('color-theme', 'dark')
        } else {
            document.documentElement.classList.remove('dark')
            localStorage.setItem('color-theme', 'light')
        }
    })
})
</script>

<template>

    <Head title="Profile" />

    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-6">Profil Saya</p>

        <div class="space-y-6">
            <!-- User Info -->
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-teal-100 dark:bg-teal-900 rounded-full">
                    <svg class="w-8 h-8 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $page.props.auth.user?.nama }}</p>
                </div>
            </div>

            <!-- Points -->
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Poin</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $page.props.auth.user?.poin }}
                        Poin</p>
                </div>
            </div>

            <!-- Theme Toggle -->
            <div class="flex items-center justify-between px-4 py-2 ">
                <span class="text-sm text-gray-700 dark:text-gray-300">Dark Mode</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="theme-toggle" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                    </div>
                </label>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col gap-4">
                    <button @click="testNotification"
                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Coba Notifikasi
                    </button>

                    <Link :href="route('child.index')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Pengaturan Data Anak
                    </Link>

                    <Link :href="route('profile.edit')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition">

                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Edit Profil
                    </Link>

                    <Link :href="route('password.edit')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Ganti Kata Sandi
                    </Link>

                    <Link :href="route('logout')" method="post" as="button"
                        class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
