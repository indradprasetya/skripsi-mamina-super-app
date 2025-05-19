<script setup>
import { ref, computed, onMounted } from 'vue';
import { Scatter } from 'vue-chartjs';
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

const props = defineProps({ child: Object });

const refData = ref([]);
const loading = ref(true);

onMounted(async () => {
    loading.value = true;
    const gender = props.child.jenis_kelamin === 'L' ? 'laki' : 'perempuan';
    const { data } = await axios.get(route('antropometri.get', { tipe: 'bbtb', gender }));
    refData.value = data;
    loading.value = false;
});

const chartData = computed(() => {
    if (!refData.value.length || !props.child.records.length) return null;
    const age = Number(props.child.records[0].umur);
    const umurRef = age <= 24 ? 2 : 5;
    const filteredRefs = refData.value.filter(r => Number(r.umur) === umurRef);
    const minHeight = umurRef === 2 ? 45 : 65;
    const heights = filteredRefs
        .map(r => Number(r.height))
        .filter((v, i, arr) => arr.indexOf(v) === i && v >= minHeight)
        .sort((a, b) => a - b);
    const lines = [
        { label: '-3SD', color: '#000', key: 'sd3neg', data: [] },
        { label: '-2SD', color: '#e11d48', key: 'sd2neg', data: [] },
        { label: '-1SD', color: '#f59e42', key: 'sd1neg', data: [] },
        { label: '0SD', color: '#22c55e', key: 'sd0', data: [] },
        { label: '1SD', color: '#f59e42', key: 'sd1', data: [] },
        { label: '2SD', color: '#e11d48', key: 'sd2', data: [] },
        { label: '3SD', color: '#000', key: 'sd3', data: [] },
    ];
    heights.forEach(height => {
        const ref = filteredRefs.find(r => Number(r.height) === height);
        if (ref) {
            lines.forEach(line => {
                line.data.push({ x: height, y: Number(ref[line.key]) });
            });
        }
    });

    // Create points for all records
    const recordPoints = props.child.records.map(record => ({
        x: Number(record.tinggi_badan),
        y: Number(record.berat_badan)
    }));

    // Cari min y dari semua garis SD
    const allY = lines.flatMap(line => line.data.map(d => d.y));
    const minY = Math.floor(Math.min(...allY));

    return { lines, recordPoints, minHeight, minY };
});

const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        title: {
            display: true,
            text: `Grafik BB/TB ${props.child.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}`,
            color: '#0f172a',
            font: { size: 18, weight: 'bold' }
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    const point = context.raw;
                    return [
                        `Tinggi Badan: ${point.x} cm`,
                        `Berat Badan: ${point.y} kg`
                    ];
                }
            }
        }
    },
    scales: {
        x: {
            title: { display: true, text: 'Tinggi Badan (cm)' },
            min: chartData.value?.minHeight || 45,
            grid: {
                color: '#e5e7eb'
            },
            ticks: {
                color: '#374151'
            }
        },
        y: {
            title: { display: true, text: 'Berat Badan (kg)' },
            min: chartData.value?.minY ?? 0,
            grid: {
                color: '#e5e7eb'
            },
            ticks: {
                color: '#374151'
            }
        }
    }
}));

function calculateZScoreRumus(Y, L, M, S) {
    if (!Y || !L || !M || !S) return null;
    return (((Math.pow(Y / M, L) - 1) / (L * S))).toFixed(2);
}

// Fungsi penjelasan status gizi BB/TB
function explainBBTB(z) {
    if (z === null || z === undefined || isNaN(z)) return '-';
    z = parseFloat(z);
    if (z < -3) return 'Gizi buruk (severely wasted)';
    if (z >= -3 && z < -2) return 'Gizi kurang (wasted)';
    if (z >= -2 && z <= 1) return 'Gizi baik (normal)';
    if (z > 1 && z <= 2) return 'Berisiko gizi lebih (possible risk of overweight)';
    if (z > 2 && z <= 3) return 'Gizi lebih (overweight)';
    if (z > 3) return 'Obesitas (obese)';
    return '-';
}

const zScore = computed(() => {
    if (!props.child.records[0] || !refData.value.length) return null;
    const tinggi = Number(props.child.records[0].tinggi_badan);
    const berat = Number(props.child.records[0].berat_badan);
    const age = Number(props.child.records[0].umur);
    const umurRef = age <= 24 ? 2 : 5;
    const ref = refData.value.find(r => Number(r.umur) === umurRef && Number(r.height) === tinggi);
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
        <!-- Status Gizi BB/TB -->
        <div class="mt-4">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">BB/TB (Z-Score)</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                            {{ zScore }}
                        </span>
                        | {{ explainBBTB(zScore) }}
                    </p>
                </div>
            </div>
        </div>

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
                        pointRadius: 5,
                        pointStyle: 'circle',
                        type: 'scatter',
                        order: 2
                    }
                ]
            }" :options="chartOptions" style="height: 400px" />
        </div>


    </div>
</template>
