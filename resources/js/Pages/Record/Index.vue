<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';


const props = defineProps({
    child: Object,
    records: Array,
    filters: Object
});

const search = ref(props.filters.search || '');

watch(search, debounce((newSearch) => {
    const params = {};
    if (newSearch) params.search = newSearch;

    router.get(
        route('records.index', props.child.id_anak),
        params,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}, 300));

const hapus = (id) => {
    if (confirm('Yakin ingin menghapus data ini?')) {
        router.delete(route('records.destroy', id));
    }
};

const getDateIndonesia = date =>
    new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });

const resetSearch = () => {
    search.value = '';
    router.get(
        route('records.index', props.child.id_anak),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};
</script>

<template>

    <Head title="Records" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-6">
            Riwayat {{ child.nama }}
        </p>

        <div class="mt-6 mb-4 items-center">
            <div class="mt-5 mb-4">
                <Link :href="route('records.create', child.id_anak)"
                    class="bg-teal-600 text-white px-4 py-2 rounded-md text-sm">
                Tambah Data Tumbuh Kembang
                </Link>
            </div>
            <div class="w-full flex gap-2 items-center">
                <label for="search" class="text-sm text-gray-500 dark:text-gray-400">Cari Tanggal</label>
                <input type="date" id="search" v-model="search"
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 px-3 py-2" />
                <button @click="resetSearch" type="button"
                    class="px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-sm">Reset</button>
            </div>
        </div>

        <div class="space-y-4">
            <div v-for="record in records" :key="record.id_tumbuhkembang"
                class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal: {{ getDateIndonesia(record.tanggal_catatan)
                    }}</p>
                <div class="mt-2 space-y-1">
                    <p class="text-base text-gray-800 dark:text-gray-100">Berat Badan: {{ record.berat_badan }} kg</p>
                    <p class="text-base text-gray-800 dark:text-gray-100">Tinggi Badan: {{ record.tinggi_badan }} cm</p>
                    <p class="text-base text-gray-800 dark:text-gray-100">Lingkar Kepala: {{ record.lingkar_kepala }} cm
                    </p>
                </div>
                <div class="flex gap-2 mt-2">
                    <Link :href="route('records.edit', record.id_tumbuhkembang)"
                        class="text-sm bg-blue-500 text-white px-3 py-1 rounded">Edit</Link>
                    <button @click="hapus(record.id_tumbuhkembang)"
                        class="text-sm bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</template>
