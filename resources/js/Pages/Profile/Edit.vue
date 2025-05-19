<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import Select from '@/Components/Select.vue';
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    user: Object,
    provinsi: Array,
    cabang: Array,
});

const form = useForm({
    telp: props.user?.telp || '',
    jenis_kelamin: props.user?.jenis_kelamin || '',
    alamat: props.user?.alamat || '',
    id_provinsi: props.user?.id_provinsi || '',
    id_kota: props.user?.id_kota || '',
    id_kecamatan: props.user?.id_kecamatan || '',
    id_kelurahan: props.user?.id_kelurahan || '',
    id_cabang: props.user?.id_cabang || '',
});

const kota = ref([]);
const kecamatan = ref([]);
const kelurahan = ref([]);

// Load initial data
if (form.id_provinsi) {
    axios.get(route('lokasi.kota', { id_provinsi: form.id_provinsi }))
        .then(response => {
            kota.value = response.data;
        });
}

if (form.id_kota) {
    axios.get(route('lokasi.kecamatan', { id_kota: form.id_kota }))
        .then(response => {
            kecamatan.value = response.data;
        });
}

if (form.id_kecamatan) {
    axios.get(route('lokasi.kelurahan', { id_kecamatan: form.id_kecamatan }))
        .then(response => {
            kelurahan.value = response.data;
        });
}

watch(() => form.id_provinsi, async (val) => {
    form.id_kota = form.id_kecamatan = form.id_kelurahan = '';
    if (val) {
        const res = await axios.get(route('lokasi.kota', { id_provinsi: val }));
        kota.value = res.data;
    }
});

watch(() => form.id_kota, async (val) => {
    form.id_kecamatan = form.id_kelurahan = '';
    if (val) {
        const res = await axios.get(route('lokasi.kecamatan', { id_kota: val }));
        kecamatan.value = res.data;
    }
});

watch(() => form.id_kecamatan, async (val) => {
    form.id_kelurahan = '';
    if (val) {
        const res = await axios.get(route('lokasi.kelurahan', { id_kecamatan: val }));
        kelurahan.value = res.data;
    }
});

const submit = () => {
    form.put(route('profile.update'), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
        }
    });
};
</script>

<template>
    <Head title="Edit Profil" />

    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-6">Edit Profil</p>

        <form @submit.prevent="submit">
            <!-- Telepon -->
            <TextInput id="telp" name="Nomor Telepon" v-model="form.telp" :message="form.errors.telp" />

            <!-- Jenis Kelamin -->
            <div class="mb-4">
                <label for="jk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                <select id="jk" v-model="form.jenis_kelamin"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-teal-500 dark:focus:border-teal-500"
                    :class="{ '!ring-1 !ring-red-500 !border-red-500': form.errors.jenis_kelamin }">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
                <small class="text-red-500" v-if="form.errors.jenis_kelamin">{{ form.errors.jenis_kelamin }}</small>
            </div>

            <!-- Alamat -->
            <TextArea name="Alamat Rumah" id="alamat" v-model="form.alamat" :message="form.errors.alamat" />

            <!-- Provinsi -->
            <Select name="Provinsi" id="provinsi" v-model="form.id_provinsi" :options="provinsi"
                optionValue="id_provinsi" optionLabel="nama_provinsi" :message="form.errors.id_provinsi" />

            <!-- Kota -->
            <Select name="Kota" id="kota" v-model="form.id_kota" :options="kota" optionValue="id_kota"
                optionLabel="nama_kota" :message="form.errors.id_kota" :disabled="!form.id_provinsi" />

            <!-- Kecamatan -->
            <Select name="Kecamatan" id="kecamatan" v-model="form.id_kecamatan" :options="kecamatan"
                optionValue="id_kecamatan" optionLabel="nama_kecamatan" :message="form.errors.id_kecamatan"
                :disabled="!form.id_kota" />

            <!-- Kelurahan -->
            <Select name="Kelurahan" id="kelurahan" v-model="form.id_kelurahan" :options="kelurahan"
                optionValue="id_kelurahan" optionLabel="nama_kelurahan" :message="form.errors.id_kelurahan"
                :disabled="!form.id_kecamatan" />

            <!-- Cabang -->
            <Select name="Pilih Cabang Mamina Terdekat (Opsional)" id="cabang" v-model="form.id_cabang"
                :options="cabang" optionValue="id_cabang" optionLabel="nama_cabang" :message="form.errors.id_cabang" />

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 mt-3">
                <Link :href="route('profile.index')"
                    class="text-white bg-gray-600 hover:opacity-50 focus:ring-gray-600 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-400 dark:hover:opacity-50 dark:focus:ring-gray-400">
                    Batal
                </Link>
                <button type="submit"
                    class="text-white bg-teal-600 hover:opacity-50 focus:ring-teal-600 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-teal-400 dark:hover:opacity-50 dark:focus:ring-teal-400 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="form.processing">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</template>
