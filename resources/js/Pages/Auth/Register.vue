<script setup>
import axios from 'axios';
import { ref, watch } from 'vue'
import { useForm } from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";
import TextArea from "../../Components/TextArea.vue";
import Select from "../../Components/Select.vue";

const props = defineProps({ provinsi: Array, cabang: Array })

const form = useForm({
    telp: null,
    email: null,
    nama: null,
    jenis_kelamin: null,
    tgl_lahir: null,
    id_provinsi: null,
    id_kota: null,
    id_kecamatan: null,
    id_kelurahan: null,
    id_cabang: null,
    alamat: null,
    password: null,
    password_confirmation: null,
});

const kota = ref([])
const kecamatan = ref([])
const kelurahan = ref([])

watch(() => form.id_provinsi, async (val) => {
    form.id_kota = form.id_kecamatan = form.id_kelurahan = ''
    if (val) {
        const res = await axios.get(route('lokasi.kota', { id_provinsi: val }))
        kota.value = res.data
    }
})

watch(() => form.id_kota, async (val) => {
    form.id_kecamatan = form.id_kelurahan = ''
    if (val) {
        const res = await axios.get(route('lokasi.kecamatan', { id_kota: val }))
        kecamatan.value = res.data
    }
})

watch(() => form.id_kecamatan, async (val) => {
    form.id_kelurahan = ''
    if (val) {
        const res = await axios.get(route('lokasi.kelurahan', { id_kecamatan: val }))
        kelurahan.value = res.data
    }
})

const submit = () => {
    form.post(route("register"), {
        onError: () => form.reset('password', 'password_confirmation')
    });
};
</script>


<template>
    <Head title="Register" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-5">
            Registrasi Pelanggan
        </p>

        <form @submit.prevent="submit">
            <TextInput name="Nomor Telepon" id="telp" v-model="form.telp" :message="form.errors.telp"
                placeholder="62807504124" />
            <TextInput name="Email" id="email" type="email" v-model="form.email" :message="form.errors.email" />
            <TextInput name="Nama Lengkap" id="nama" v-model="form.nama" :message="form.errors.nama" />
            <div class="mb-4">
                <label for="jk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                    Kelamin</label>
                <select id="jk" v-model="form.jenis_kelamin"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-teal-500 dark:focus:border-teal-500"
                    :class="{ '!ring-1 !ring-red-500 !border-red-500': form.errors.jenis_kelamin, }">
                    <option value="L">Laki - Laki</option>
                    <option value="P">Perempuan</option>
                </select>
                <small class="text-red-500" v-if="form.errors.jenis_kelamin">{{ form.errors.jenis_kelamin }}</small>
            </div>
            <TextInput name="Tanggal Lahir" id="tanggal-lahir" type="date" v-model="form.tgl_lahir"
                :message="form.errors.tgl_lahir" />
            <TextArea name="Alamat Rumah" id="alamat" v-model="form.alamat" :message="form.errors.alamat" />
            <Select name="Provinsi" id="provinsi" v-model="form.id_provinsi" :options="provinsi"
                optionValue="id_provinsi" optionLabel="nama_provinsi" :message="form.errors.id_provinsi" />
            <Select name="Kota" id="kota" v-model="form.id_kota" :options="kota" optionValue="id_kota"
                optionLabel="nama_kota" :message="form.errors.id_kota" :disabled="!form.id_provinsi" />
            <Select name="Kecamatan" id="kecamatan" v-model="form.id_kecamatan" :options="kecamatan"
                optionValue="id_kecamatan" optionLabel="nama_kecamatan" :message="form.errors.id_kecamatan"
                :disabled="!form.id_kota" />
            <Select name="Kelurahan" id="kelurahan" v-model="form.id_kelurahan" :options="kelurahan"
                optionValue="id_kelurahan" optionLabel="nama_kelurahan" :message="form.errors.id_kelurahan"
                :disabled="!form.id_kecamatan" />
            <Select name="Pilih Cabang Mamina Terdekat (Opsional)" id="cabang" v-model="form.id_cabang"
                :options="cabang" optionValue="id_cabang" optionLabel="nama_cabang" :message="form.errors.id_cabang" />
            <TextInput name="Password" id="password" type="password" v-model="form.password"
                :message="form.errors.password" />
            <TextInput name="Konfirmasi Password" id="confirm-password" type="password"
                v-model="form.password_confirmation" />

            <button type="submit"
                class="mt-3 mb-2 text-white bg-teal-600 hover:opacity-50 focus:ring-teal-600 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-teal-400 dark:hover:opacity-50 dark:focus:ring-teal-400 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing">
                Registrasi
            </button>
            <small>Sudah punya Akun? <a :href="route('login')"
                    class="text-teal-600 dark:text-teal-400 font-semibold">Masuk</a></small>
        </form>
    </div>
</template>
