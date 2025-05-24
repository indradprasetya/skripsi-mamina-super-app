<template>
    <div
        class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-700 dark:border-gray-600">
        <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
            <!-- Beranda -->
            <Link :href="route('home')" type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-gray-800 group"
                :class="{ 'bg-gray-200 dark:bg-gray-800': $page.component === 'Home' }">
            <svg class="w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400"
                :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Home' }" fill="none"
                stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h4a1 1 0 001-1v-4h4v4a1 1 0 001 1h4a1 1 0 001-1V10" />
            </svg>
            <span
                class="text-2xs text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400"
                :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Home' }">Beranda</span>
            </Link>

            <!-- Nutrition -->
            <Link :href="route('nutrition.index')" type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-gray-800 group"
                :class="{ 'bg-gray-200 dark:bg-gray-800': $page.component === 'Nutrition/Index' }">
                <svg class="w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400"
                    :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Nutrition/Index' }" fill="none"
                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span
                    class="text-2xs text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 text-center"
                    :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Nutrition/Index' }">Nutrisi</span>
            </Link>

            <!-- Perkembangan -->
            <Link :href="route('growth')" type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-gray-800 group"
                :class="{ 'bg-gray-200 dark:bg-gray-800': $page.component === 'Growth' }">
            <svg class=" w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-teal-600
                dark:group-hover:text-teal-400"
                :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Growth' }" fill="none"
                stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M9 17V9m4 8v-4m4 4V5" />
            </svg>
            <span
                class="text-2xs text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400"
                :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Growth' }">Perkembangan</span>
            </Link>

            <!-- Akun -->
            <Link :href="route('profile.index')" type="button"
                class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-200 dark:hover:bg-gray-800 group"
                :class="{ 'bg-gray-200 dark:bg-gray-800': $page.component === 'Profile/Index' }">
            <svg class=" w-6 h-6 mb-1 text-gray-500 dark:text-gray-400 group-hover:text-teal-600
                dark:group-hover:text-teal-400"
                :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Profile/Index' }" fill="none"
                stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A9 9 0 1112 21a9.003 9.003 0 01-6.879-3.196zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span
                class="text-2xs text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400"
                :class="{ 'text-teal-600 dark:text-teal-400': $page.component === 'Profile/Index' }">Akun</span>
            </Link>

        </div>
    </div>
</template>
