<script setup>
import { useForm } from '@inertiajs/vue3';
import TextInput from '../../Components/TextInput.vue';

const props = defineProps({
    child: Object,
})

const form = useForm({
    nama: props.child.nama,
    jenis_kelamin: props.child.jenis_kelamin,
    tgl_lahir: props.child.tgl_lahir,
    tempat: props.child.tempat,
})

const submit = () => {
    form.put(route('child.update', props.child.id_anak))
}
</script>

<template>
    <Head title="Edit Child" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-4">Edit Data Anak</p>

        <form @submit.prevent="submit">
            <TextInput id="nama" name="Nama Anak" v-model="form.nama" :message="form.errors.nama" />

            <div class="mb-4">
                <label for="jk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                    Kelamin</label>
                <select id="jk" v-model="form.jenis_kelamin"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-teal-500 dark:focus:border-teal-500"
                    :class="{ '!ring-1 !ring-red-500 !border-red-500': form.errors.jenis_kelamin }">
                    <option value="L">Laki - Laki</option>
                    <option value="P">Perempuan</option>
                </select>
                <small class="text-red-500" v-if="form.errors.jenis_kelamin">{{ form.errors.jenis_kelamin }}</small>
            </div>

            <TextInput id="tempat" name="Tempat Tanggal Lahir" v-model="form.tempat" :message="form.errors.tempat" />
            <TextInput id="tgl_lahir" name="Tanggal Lahir" type="date" v-model="form.tgl_lahir"
                :message="form.errors.tgl_lahir" />

            <button type="submit"
                class="mt-3 mb-2 text-white bg-teal-600 hover:opacity-50 focus:ring-teal-600 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-teal-400 dark:hover:opacity-50 dark:focus:ring-teal-400 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing">
                Simpan Perubahan
            </button>
        </form>
    </div>
</template>
