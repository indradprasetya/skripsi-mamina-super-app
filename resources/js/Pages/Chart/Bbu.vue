<script setup>
import { ref, computed, onMounted } from 'vue';
import { Scatter } from 'vue-chartjs';
import { getDateIndonesia } from '../../utils/date';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
} from 'chart.js';
import axios from 'axios';

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
);


// ngambil data dari parent
const props = defineProps({ child: Object });

const refData = ref([]); // referensi data
const loading = ref(true);

onMounted(async () => {
    loading.value = true;
    const gender = props.child.jenis_kelamin === 'L' ? 'laki' : 'perempuan';
    const { data } = await axios.get(route('antropometri.get', { tipe: 'bbu', gender }));
    refData.value = data;
    loading.value = false;
});

const chartData = computed(() => {
    if (!refData.value.length || !props.child.records.length) return null;
    const months = Array.from({ length: 61 }, (_, i) => i);
    const lines = [
        { label: '-3SD', color: '#000', key: 'sd3neg', data: [] },
        { label: '-2SD', color: '#e11d48', key: 'sd2neg', data: [] },
        { label: '-1SD', color: '#f59e42', key: 'sd1neg', data: [] },
        { label: '0SD', color: '#22c55e', key: 'sd0', data: [] },
        { label: '1SD', color: '#f59e42', key: 'sd1', data: [] },
        { label: '2SD', color: '#e11d48', key: 'sd2', data: [] },
        { label: '3SD', color: '#000', key: 'sd3', data: [] },
    ];
    months.forEach(month => {
        const ref = refData.value.find(r => Number(r.month) === month);
        if (ref) {
            lines.forEach(line => {
                line.data.push({ x: month, y: Number(ref[line.key]) });
            });
        }
    });
    // Create points for all records
    const recordPoints = props.child.records.map(record => ({
        x: Number(record.umur),
        y: Number(record.berat_badan)
    }));
    // Cari min y dari semua garis SD
    const allY = lines.flatMap(line => line.data.map(d => d.y));
    const minY = Math.floor(Math.min(...allY));
    return { lines, recordPoints, minY };
});

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        title: {
            display: true,
            text: `Grafik BB/U ${props.child.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}`,
            color: '#0f172a',
            font: { size: 18, weight: 'bold' }
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    const point = context.raw;
                    return [
                        `Umur: ${point.x} bulan`,
                        `Berat Badan: ${point.y} kg`
                    ];
                }
            }
        }
    },
    scales: {
        x: {
            title: { display: true, text: 'Umur (bulan)' },
            min: 0,
            max: 60,
            grid: { color: '#e5e7eb' },
            ticks: { color: '#374151' }
        },
        y: {
            title: { display: true, text: 'Berat Badan (kg)' },
            min: chartData.value?.minY ?? 0,
            grid: { color: '#e5e7eb' },
            ticks: { color: '#374151' }
        }
    }
}));

function calculateZScoreRumus(Y, L, M, S) {
    if (!Y || !L || !M || !S) return null;

    return (((Math.pow(Y / M, L) - 1) / (L * S))).toFixed(2);
}

// Penjelasan status gizi BB/U
function explainBBU(z) {
    if (z === null || z === undefined || isNaN(z)) return '-';
    z = parseFloat(z);
    if (z < -3) return 'Berat badan sangat kurang (severely underweight)';
    if (z >= -3 && z < -2) return 'Berat badan kurang (underweight)';
    if (z >= -2 && z <= 1) return 'Berat badan normal';
    if (z > 1) return 'Risiko berat badan lebih';
    return '-';
}

const zScore = computed(() => {
    if (!props.child.records[0] || !refData.value.length) return null;
    const umur = Number(props.child.records[0].umur);
    const berat = Number(props.child.records[0].berat_badan);
    const ref = refData.value.find(r => Number(r.month) === umur);
    if (!ref) return null;
    return calculateZScoreRumus(
        berat,
        Number(ref.l),
        Number(ref.m),
        Number(ref.s)
    );
});


</script>

<template>
    <div v-if="loading">Memuat data referensi...</div>
    <div v-else>
        <!-- Status Gizi BB/U -->
        <div :class="[
            'rounded-lg p-4 mb-2 flex items-center shadow-md gap-4',
            zScore >= -2 && zScore <= 2 ? 'bg-green-100' :
                (zScore >= -3 && zScore < -2) || (zScore > 2 && zScore <= 3) ? 'bg-yellow-100' :
                    'bg-red-100 '
        ]">
            <img :src="`/img/face/${zScore >= -2 && zScore <= 2 ? 'happy' :
                ((zScore >= -3 && zScore < -2) || (zScore > 2 && zScore <= 3)) ? 'flat' :
                    'unhappy'}.png`" class="w-14 h-20 mr-4 flex-shrink-0" alt="Status Gizi" />
            <div>
                <p class="text-xs font-base text-gray-800">Berdasarkan data BB/U</p>
                <p :class="[
                    'text-md font-bold mb-1',
                    zScore >= -2 && zScore <= 2 ? 'text-green-800' :
                        (zScore >= -3 && zScore < -2) || (zScore > 2 && zScore <= 3) ? 'text-yellow-800' :
                            'text-red-800'
                ]">
                    {{ explainBBU(zScore) }}
                </p>
                <p class="text-xs text-gray-700 font-medium">Z-score: <span class="font-mono">{{ zScore > 0 ? '+' :
                    '' }}{{ zScore }} SD</span></p>
                <p class="text-2xs text-gray-500 ">Data terakhir: {{
                    getDateIndonesia(props.child.records[0]?.tanggal_catatan) }}</p>
            </div>
        </div>

        <p>
            <Link :href="route('zscore.info')"
                class="text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 text-xs">Apa itu
            Z-score? Klik untuk pelajari
            </Link>
        </p>

        <!-- Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 mt-4">
            <Scatter v-if="chartData" :data="{
                datasets: [
                    ...chartData.lines.map(line => ({
                        label: line.label,
                        data: line.data,
                        showLine: true,
                        borderColor: line.color,
                        backgroundColor: line.color,
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false,
                        order: 1
                    })),
                    {
                        label: 'Data Anak',
                        data: chartData.recordPoints,
                        backgroundColor: '#0ea5e9',
                        borderColor: '#0ea5e9',
                        pointRadius: 3,
                        pointStyle: 'circle',
                        type: 'scatter',
                        order: 2
                    }
                ]
            }" :options="chartOptions" style="height: 400px" />
        </div>


    </div>
</template>
