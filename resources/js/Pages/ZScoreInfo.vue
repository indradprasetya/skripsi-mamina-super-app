<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Informasi Z-Score" />
    <div class="py-6">
        <Link :href="route('growth')"
            class="mb-6 flex items-center text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Data Perkembangan
        </Link>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Informasi Z-Score</h2>

            <div class="prose dark:prose-invert max-w-none">
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Standar Antropometri Anak digunakan untuk menilai atau menentukan status gizi anak.
                    Penilaian status gizi Anak dilakukan dengan membandingkan hasil pengukuran berat badan
                    dan panjang/tinggi badan dengan Standar Antropometri Anak. Klasifikasi penilaian status
                    gizi berdasarkan Indeks Antropometri sesuai dengan kategori status gizi pada WHO Child
                    Growth Standards untuk anak usia 0-5 tahun dan The WHO Reference 2007 untuk anak 5-18 tahun.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-4">Perhitungan Umur</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Umur yang digunakan pada standar ini merupakan umur yang dihitung dalam bulan penuh,
                    sebagai contoh bila umur anak 2 bulan 29 hari maka dihitung sebagai umur 2 bulan.
                    Indeks Panjang Badan (PB) digunakan pada anak umur 0-24 bulan yang diukur dengan posisi
                    terlentang. Bila anak umur 0-24 bulan diukur dengan posisi berdiri, maka hasil
                    pengukurannya dikoreksi dengan menambahkan 0,7 cm. Sementara untuk indeks Tinggi Badan (TB)
                    digunakan pada anak umur di atas 24 bulan yang diukur dengan posisi berdiri. Bila anak
                    umur di atas 24 bulan diukur dengan posisi terlentang, maka hasil pengukurannya dikoreksi
                    dengan mengurangkan 0,7 cm.
                </p>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-6 mb-4">Indeks Standar Antropometri
                    Anak</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Standar Antropometri Anak didasarkan pada parameter berat badan dan panjang/tinggi badan
                    yang terdiri atas 4 (empat) indeks, meliputi:
                </p>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">1. Indeks Berat Badan
                            menurut Umur (BB/U)</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Indeks BB/U ini menggambarkan berat badan relatif dibandingkan dengan umur anak.
                            Indeks ini digunakan untuk menilai anak dengan berat badan kurang (underweight) atau
                            sangat kurang (severely underweight), tetapi tidak dapat digunakan untuk mengklasifikasikan
                            anak gemuk atau sangat gemuk. Penting diketahui bahwa seorang anak dengan BB/U rendah,
                            kemungkinan mengalami masalah pertumbuhan, sehingga perlu dikonfirmasi dengan indeks
                            BB/PB atau BB/TB atau IMT/U sebelum diintervensi.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">2. Indeks Panjang Badan
                            menurut Umur atau Tinggi Badan menurut Umur (PB/U atau TB/U)</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Indeks PB/U atau TB/U menggambarkan pertumbuhan panjang atau tinggi badan anak berdasarkan
                            umurnya. Indeks ini dapat mengidentifikasi anak-anak yang pendek (stunted) atau sangat
                            pendek (severely stunted), yang disebabkan oleh gizi kurang dalam waktu lama atau sering
                            sakit.
                            Anak-anak yang tergolong tinggi menurut umurnya juga dapat diidentifikasi. Anak-anak dengan
                            tinggi badan di atas normal (tinggi sekali) biasanya disebabkan oleh gangguan endokrin,
                            namun hal ini jarang terjadi di Indonesia.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">3. Indeks Berat Badan
                            menurut Panjang Badan/Tinggi Badan (BB/PB atau BB/TB)</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Indeks BB/PB atau BB/TB ini menggambarkan apakah berat badan anak sesuai terhadap
                            pertumbuhan
                            panjang/tinggi badannya. Indeks ini dapat digunakan untuk mengidentifikasi anak gizi kurang
                            (wasted), gizi buruk (severely wasted) serta anak yang memiliki risiko gizi lebih (possible
                            risk of overweight). Kondisi gizi buruk biasanya disebabkan oleh penyakit dan kekurangan
                            asupan gizi yang baru saja terjadi (akut) maupun yang telah lama terjadi (kronis).
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">4. Indeks Masa Tubuh
                            menurut Umur (IMT/U)</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Indeks IMT/U digunakan untuk menentukan kategori gizi buruk, gizi kurang, gizi baik,
                            berisiko
                            gizi lebih, gizi lebih dan obesitas. Grafik IMT/U dan grafik BB/PB atau BB/TB cenderung
                            menunjukkan hasil yang sama. Namun indeks IMT/U lebih sensitif untuk penapisan anak gizi
                            lebih
                            dan obesitas. Anak dengan ambang batas IMT/U >+1SD berisiko gizi lebih sehingga perlu
                            ditangani lebih lanjut untuk mencegah terjadinya gizi lebih dan obesitas.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
