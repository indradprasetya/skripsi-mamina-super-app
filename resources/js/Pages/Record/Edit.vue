<script setup>
import { useForm } from '@inertiajs/vue3';
import TextInput from '../../Components/TextInput.vue';

const props = defineProps({
    record: Object,
});

const form = useForm({
    tanggal_catatan: props.record.tanggal_catatan,
    berat_badan: props.record.berat_badan,
    tinggi_badan: props.record.tinggi_badan,
    lingkar_kepala: props.record.lingkar_kepala,
});

const submit = () => {
    form.put(route('records.update', props.record.id_tumbuhkembang), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Edit Record" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-4">Edit Data Perkembangan</p>

        <form @submit.prevent="submit">
            <TextInput id="tanggal_catatan" name="Tanggal Catatan" type="date" v-model="form.tanggal_catatan"
                :message="form.errors.tanggal_catatan" />
            <TextInput id="berat_badan" name="Berat Badan (kg)" type="number" step="0.01" v-model="form.berat_badan"
                :message="form.errors.berat_badan" />
            <TextInput id="tinggi_badan" name="Tinggi Badan (cm)" type="number" step="0.01" v-model="form.tinggi_badan"
                :message="form.errors.tinggi_badan" />
            <TextInput id="lingkar_kepala" name="Lingkar Kepala (cm)" type="number" step="0.01"
                v-model="form.lingkar_kepala" :message="form.errors.lingkar_kepala" />

            <button type="submit"
                class="mt-3 mb-2 text-white bg-teal-600 hover:opacity-50 focus:ring-teal-600 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-teal-400 dark:hover:opacity-50 dark:focus:ring-teal-400 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing">
                Simpan
            </button>
        </form>
    </div>
</template>
