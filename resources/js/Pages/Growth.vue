<script setup>
import { ref, computed, watchEffect, defineAsyncComponent } from 'vue';
import Select from "../Components/Select.vue";


const props = defineProps({
    children: Array
});

const selectedChildId = ref(null);
const selectedChartType = ref('bbu');

const chartTypes = [
    { value: 'bbu', label: 'Berat Badan/Umur (BB/U)' },
    { value: 'tbu', label: 'Tinggi Badan/Umur (TB/U)' },
    { value: 'bbtb', label: 'Berat Badan/Tinggi Badan (BB/TB)' },
    { value: 'imtu', label: 'IMT/Umur (IMT/U)' },
    { value: 'lku', label: 'Lingkar Kepala/Umur (LKU)' }
];

const chartComponentMap = {
    bbu: defineAsyncComponent(() => import('./Chart/Bbu.vue')),
    tbu: defineAsyncComponent(() => import('./Chart/Tbu.vue')),
    bbtb: defineAsyncComponent(() => import('./Chart/Bbtb.vue')),
    imtu: defineAsyncComponent(() => import('./Chart/Imtu.vue')),
    lku: defineAsyncComponent(() => import('./Chart/Lku.vue'))
};

watchEffect(() => {
    if (props.children.length > 0 && selectedChildId.value === null) {
        selectedChildId.value = props.children[0].id_anak;
    }
});

const selectedChild = computed(() =>
    props.children.find(child => child.id_anak === selectedChildId.value)
);

const currentChartComponent = computed(() => chartComponentMap[selectedChartType.value]);

const getAgeInMonths = (birthdateStr) => {
    const birthdate = new Date(birthdateStr);
    const now = new Date();
    let months = (now.getFullYear() - birthdate.getFullYear()) * 12 + (now.getMonth() - birthdate.getMonth());
    if (now.getDate() < birthdate.getDate()) {
        months--;
    }
    const years = Math.floor(months / 12);
    const remainingMonths = months % 12;
    return {
        years,
        months: remainingMonths,
        totalMonths: months
    };
};

// ngubah jadi format lokal yang mudah dibaca orang indonesia
const getDateIndonesia = date =>
    new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
</script>

<template>

    <Head title="Growth" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-6">Perkembangan</p>

        <div v-if="children.length === 0" class="text-sm text-gray-400 my-4">
            <p class="text-sm text-gray-400 mb-5">Belum ada data anak.</p>

            <Link :href="route('child.index')" class="bg-teal-600 text-white px-4 py-2 rounded-md text-sm">
            Tambah Data Anak
            </Link>
        </div>

        <div v-else>

            <Select name="Pilih Data Perkembangan Anak" id="child" v-model="selectedChildId" :options="props.children"
                optionValue="id_anak" optionLabel="nama" />
            <!-- Data Anak -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 mt-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Data Anak
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-teal-100 dark:bg-teal-900 rounded-lg">
                                <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nama Anak</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">{{
                                    selectedChild.nama }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Umur Saat Ini</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    <span v-if="getAgeInMonths(selectedChild.tgl_lahir).years > 0">
                                        {{ getAgeInMonths(selectedChild.tgl_lahir).years }} tahun
                                        <span v-if="getAgeInMonths(selectedChild.tgl_lahir).months > 0">
                                            {{ getAgeInMonths(selectedChild.tgl_lahir).months }} bulan
                                        </span>
                                        ({{ getAgeInMonths(selectedChild.tgl_lahir).totalMonths }} bulan)
                                    </span>
                                    <span v-else>
                                        {{ getAgeInMonths(selectedChild.tgl_lahir).totalMonths }} bulan
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ selectedChild.jenis_kelamin === 'L' ? 'Laki-laki' :
                                        selectedChild.jenis_kelamin === 'P' ? 'Perempuan' : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cek apakah umur anak sudah 5tahun/60bulan -->
            <div v-if="getAgeInMonths(selectedChild.tgl_lahir).totalMonths > 60"
                class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full mb-4">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Umur Anak Melebihi Batas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                    Anak sudah mencapai usia 5 tahun (60 bulan).<br>
                    Grafik pertumbuhan tidak dapat ditampilkan karena melebihi batas usia yang direkomendasikan.
                </p>
            </div>

            <div v-else>
                <!-- Cek apakah data anak kosong atau tidak  -->
                <div v-if="selectedChild.records.length == 0">
                    <p class="text-sm text-gray-400">Belum ada data perkembangan.</p>

                    <div class="mt-4">
                        <Link v-if="selectedChildId" :href="route('records.index', selectedChildId)"
                            class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-md transition">
                        Tambah Data Perkembangan
                        </Link>
                    </div>
                </div>

                <div v-else>

                    <Select name="Pilih Grafik" id="chartType" v-model="selectedChartType" :options="chartTypes"
                        optionValue="value" optionLabel="label" class="mt-4" />

                    <div v-if="selectedChild">
                        <component :is="currentChartComponent" :child="selectedChild" />
                    </div>

                    <div class="mt-6 mb-6 ">
                        <Link v-if="selectedChildId" :href="route('records.index', selectedChildId)"
                            class="bg-teal-600 hover:bg-teal-700 text-white text-sm px-4 py-2 rounded-md transition w-full">
                        Tambah Data / Lihat Riwayat Perkembangan
                        </Link>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Informasi Pengukuran Terakhir
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pengukuran</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white">
                                            {{ getDateIndonesia(selectedChild.records[0].tanggal_catatan) }} ({{
                                                selectedChild.records[0].umur }}
                                            bulan)
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Berat Badan</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white">{{
                                            selectedChild.records[0].berat_badan }} kg</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tinggi Badan/Panjang Badan
                                        </p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white">{{
                                            selectedChild.records[0].tinggi_badan }} cm</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none"
                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 12a9 9 0 11-3.07-6.84" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Lingkar Kepala</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white">{{
                                            selectedChild.records[0].lingkar_kepala }} cm</p>
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
