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
        <div :class="[
            'rounded-lg p-4 mb-6 flex items-center shadow-md gap-4',
            zScore >= -2 && zScore <= 2 ? 'bg-green-100' :
                (zScore >= -3 && zScore < -2) || (zScore > 2 && zScore <= 3) ? 'bg-yellow-100' :
                    'bg-red-100 '
        ]">
            <img :src="`/img/face/${zScore >= -2 && zScore <= 2 ? 'happy' :
                ((zScore >= -3 && zScore < -2) || (zScore > 2 && zScore <= 3)) ? 'flat' :
                    'unhappy'}.png`" class="w-14 h-20 mr-4 flex-shrink-0" alt="Status Gizi" />
            <div>
                <p class="text-sm font-base text-gray-800">Berdasarkan data BB/TB</p>
                <p :class="[
                    'text-md font-bold',
                    zScore >= -2 && zScore <= 2 ? 'text-green-800' :
                        (zScore >= -3 && zScore < -2) || (zScore > 2 && zScore <= 3) ? 'text-yellow-800' :
                            'text-red-800'
                ]">
                    {{ explainBBTB(zScore) }}
                </p>
                <p class="text-sm mt-1 text-gray-700 font-medium">Z-score: <span class="font-mono">{{ zScore > 0 ? '+' :
                        '' }}{{ zScore }} SD</span></p>
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
