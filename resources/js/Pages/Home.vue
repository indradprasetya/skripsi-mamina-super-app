<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'

onMounted(() => {
    initFlowbite();
})
</script>

<template>

    <Head :title="$page.component" />
    <!-- Welcome -->
    <div class="py-6">
        <div id="welcome-text">
            <p class="text-xl font-bold text-teal-600 dark:text-teal-400">Halo, Selamat Datang</p>
            <p class="text-sm font-medium text-gray-600 dark:text-white" v-if="$page.props.auth.user"> {{
                $page.props.auth.user.jenis_kelamin === 'L' ? 'Ayah' : 'Bunda' }} {{ $page.props.auth.user.nama }}
            </p>
        </div>
        <!-- Caraousel -->
        <div id="default-carousel" class="relative w-full mt-4" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-36 overflow-hidden rounded-lg md:h-76">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="https://placehold.co/600x400"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="https://placehold.co/600x400"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
                <!-- Item 3 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="https://placehold.co/600x400"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
                <!-- Item 4 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="https://placehold.co/600x400"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
                <!-- Item 5 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="https://placehold.co/600x400"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
            </div>
            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                <button type="button" class="w-1 h-1 rounded-full" aria-current="true" aria-label="Slide 1"
                    data-carousel-slide-to="0"></button>
                <button type="button" class="w-1 h-1 rounded-full" aria-current="false" aria-label="Slide 2"
                    data-carousel-slide-to="1"></button>
                <button type="button" class="w-1 h-1 rounded-full" aria-current="false" aria-label="Slide 3"
                    data-carousel-slide-to="2"></button>
                <button type="button" class="w-1 h-1 rounded-full" aria-current="false" aria-label="Slide 4"
                    data-carousel-slide-to="3"></button>
                <button type="button" class="w-1 h-1 rounded-full" aria-current="false" aria-label="Slide 5"
                    data-carousel-slide-to="4"></button>
            </div>
            <!-- Slider controls -->
            <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-2 h-2 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 1 1 5l4 4" />
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
            </button>
            <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-2 h-2 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
            </button>
        </div>

        <!-- Summary data anak -->
        <div id="summary-data" class="mt-4">
            <p class="text-md font-semibold text-teal-600 dark:text-teal-400">Rekap Data Perkembangan
            </p>
        </div>

        <!-- Artikel -->
        <div id="article" class="mt-4">
            <p class="text-md font-semibold text-teal-600 dark:text-teal-400">Berita dan Informasi Mamina</p>
        </div>
        <!-- Konten -->
        <div
            class="relative rounded-xl overflow-hidden bg-teal-600 dark:bg-teal-400 text-white dark:text-gray-900 mt-2">
            <!-- Background image -->
            <img src="/img/banner-article.jpg" alt="Gizi Anak" class="absolute inset-0 w-full h-full object-cover" />

            <div
                class="absolute inset-0 bg-gradient-to-r from-teal-600 via-teal-600/80 to-transparent dark:from-teal-400 dark:via-teal-400/80">
            </div>

            <!-- Konten di atas gambar -->
            <div class="relative z-10 p-4">
                <p class="font-base text-sm">
                    Temukan Informasi <br />
                    Kesehatan dan layanan <br />
                    Mamina disini 💖
                </p>
                <Link :href="route('article.index')"
                    class="inline-block mt-3 px-4 py-2 text-xs font-base text-teal-600 bg-white rounded-full hover:bg-gray-100 transition dark:text-white dark:bg-teal-800 dark:hover:bg-teal-600">
                Ayo, Pelajari Disini!
                </Link>
            </div>
        </div>
    </div>
</template>
