<script setup>
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    children: Array
});
const deleteChild = (id) => {
    if (confirm("Yakin ingin menghapus data ini?")) {
        router.delete(route('child.destroy', id));
    }
};

const getDateIndonesia = date =>
    new Date(date).toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
</script>

<template>

    <Head title="Children" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-5">
            Data Anak
        </p>

        <div class="mb-4">
            <Link :href="route('child.create')" class="bg-teal-600 text-white px-4 py-2 rounded-md text-sm">
            Tambah Anak
            </Link>
        </div>

        <div class="space-y-4">
            <div v-for="child in children" :key="child.id_anak" class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <p class="text-base font-semibold text-gray-800 dark:text-gray-100">{{ child.nama }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Lahir: {{ getDateIndonesia(child.tgl_lahir) }}</p>
                <div class="flex gap-2 mt-2">
                    <Link :href="route('child.edit', child.id_anak)"
                        class="text-sm bg-blue-500 text-white px-3 py-1 rounded">Edit</Link>
                    <button @click="deleteChild(child.id_anak)"
                        class="text-sm bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</template>
