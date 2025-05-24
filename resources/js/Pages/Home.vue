<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { initFlowbite } from 'flowbite'
import { getAgeInMonths, getDateIndonesia } from '../utils/date';
import axios from 'axios';

let carouselInterval;
const groupedServiceProducts = ref({ anak: [], ibu: [], bayi: [], kelas: [] });
const loadingProduk = ref(true);

onMounted(() => {
    initFlowbite();

    // Set up autoplay
    const carousel = document.getElementById('default-carousel');
    if (carousel) {
        carouselInterval = setInterval(() => {
            const nextButton = carousel.querySelector('[data-carousel-next]');
            if (nextButton) {
                nextButton.click();
            }
        }, 5000); // Change slide every 5 seconds
    }

    // Fetch service products
    axios.get('https://harsyadteknologi.com/internal_mmn/api/product', {
    })
        .then(response => {
            const products = response.data.data; // Access the data array from response
            //filter and group
            const groups = { anak: [], ibu: [], bayi: [], kelas: [] };
            products.forEach(product => {
                const kategori = product.kategori ? product.kategori.toLowerCase() : '';
                if (product.thumb !== null && groups[kategori]) {
                    groups[kategori].push(product);
                }
            });
            groupedServiceProducts.value = groups;
        })
        .catch(error => {
            console.error('Error fetching products:', error);
            groupedServiceProducts.value = { anak: [], ibu: [], bayi: [], kelas: [] };
        })
        .finally(() => {
            loadingProduk.value = false;
        });
});

onUnmounted(() => {
    // Clear interval when component is unmounted
    if (carouselInterval) {
        clearInterval(carouselInterval);
    }
});

const props = defineProps({
    children: Array,
    news: Array
});

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
                <div v-for="(item, index) in news" :key="item.id_news" class="hidden duration-700 ease-in-out"
                    data-carousel-item>
                    <Link :href="route('news.show', item.id_news)" class="block w-full h-full">
                    <img :src="'/img/news/' + item.thumb"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                        :alt="item.title">
                    </Link>
                </div>
            </div>
            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                <button v-for="(item, index) in news" :key="item.id_news" type="button" class="w-1 h-1 rounded-full"
                    :aria-current="index === 0 ? 'true' : 'false'" :aria-label="'Slide ' + (index + 1)"
                    :data-carousel-slide-to="index">
                </button>
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
        <div v-if="props.children && props.children.length" id="summary-data" class="mt-4">
            <p class="text-md font-semibold text-teal-600 dark:text-teal-400 mb-2">Rekap Data Perkembangan</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="child in props.children" :key="child.id_anak"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-800 dark:text-white">{{ child.nama }}</h3>
                        <span class="text-sm text-gray-600 dark:text-gray-300">
                            <span v-if="getAgeInMonths(child.tgl_lahir).years > 0">
                                {{ getAgeInMonths(child.tgl_lahir).years }} tahun
                                <span v-if="getAgeInMonths(child.tgl_lahir).months > 0">
                                    {{ getAgeInMonths(child.tgl_lahir).months }} bulan
                                </span>
                            </span>
                            <span v-else>
                                {{ getAgeInMonths(child.tgl_lahir).months }} bulan
                            </span>
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div v-if="child.records && child.records.length > 0" class="space-y-2">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="font-mono text-xs text-gray-600 dark:text-gray-300">
                                        Catatan Terakhir: {{ getDateIndonesia(child.records[0].tanggal_catatan) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 mt-2">
                                    <div class="bg-teal-100 dark:bg-teal-800 rounded-lg p-2 text-center">
                                        <p class="text-2xs font-medium text-gray-600 dark:text-gray-100">Berat Badan</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{
                                            child.records[0].berat_badan }} kg</p>
                                    </div>
                                    <div class="bg-teal-100 dark:bg-teal-800 rounded-lg p-2 text-center">
                                        <p class="text-2xs font-medium text-gray-600 dark:text-gray-100">Tinggi Badan
                                        </p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{
                                            child.records[0].tinggi_badan }} cm</p>
                                    </div>
                                    <div class="bg-teal-100 dark:bg-teal-800 rounded-lg p-2 text-center">
                                        <p class="text-2xs font-medium text-gray-600 dark:text-gray-100">Lingkar Kepala
                                        </p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{
                                            child.records[0].lingkar_kepala }} cm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-xs text-gray-400 dark:text-gray-500 italic">
                            Belum ada data
                        </div>
                    </div>
                </div>
            </div>
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
                <Link :href="route('news.index')"
                    class="inline-block mt-3 px-4 py-2 text-xs font-base text-teal-600 bg-white rounded-full hover:bg-gray-100 transition dark:text-white dark:bg-teal-800 dark:hover:bg-teal-600">
                Ayo, Pelajari Disini!
                </Link>
            </div>
        </div>

        <!-- Service Products Grouped by Kategori -->
        <div id="service-products" class="mt-4">
            <p class="text-md font-semibold text-teal-600 dark:text-teal-400 mb-2">Layanan di Mamina Mom and Baby
                Treatment</p>
            <div v-if="loadingProduk">
                <div class="text-center py-8 text-gray-400">Memuat layanan...</div>
            </div>
            <div v-else>
                <p class="text-sm text-gray-400 dark:text-gray-500 mb-2">Pendaftaran bisa dilakukan di tempat secara langsung</p>
                <div v-for="kategori in ['kelas', 'ibu', 'bayi', 'anak']" :key="kategori">
                    <div v-if="groupedServiceProducts[kategori] && groupedServiceProducts[kategori].length"
                        class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-md font-semibold text-teal-600 dark:text-teal-400 capitalize">Layanan {{
                                kategori }}</p>
                            <span class="text-xs text-gray-400 flex items-center gap-1" v-if="kategori != 'anak'">
                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Geser
                            </span>
                        </div>
                        <div class="overflow-x-auto pb-2 relative">
                            <div class="flex space-x-4">
                                <div v-for="product in groupedServiceProducts[kategori]" :key="product.id_produk"
                                    class="min-w-[180px] max-w-[180px] bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex-shrink-0">
                                    <img :src="'https://internal.mamina.id/upload/produk/thumb/' + product.thumb"
                                        :alt="product.nama_produk" class="w-full h-32 object-cover">
                                    <div class="p-3">
                                        <h3 class="text-sm font-medium text-gray-800 dark:text-white">{{
                                            product.nama_produk }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
