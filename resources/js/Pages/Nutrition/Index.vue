<script setup>
import { ref, watch, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import Select from "../../Components/Select.vue";
import TextInput from "../../Components/TextInput.vue";

const props = defineProps({
    foods: Array,
    categories: Array,
    children: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const isCalculatorOpen = ref(false);

const getAgeInMonths = (birthdateStr) => {
    const birthdate = new Date(birthdateStr);
    const now = new Date();
    let months = (now.getFullYear() - birthdate.getFullYear()) * 12 + (now.getMonth() - birthdate.getMonth());
    if (now.getDate() < birthdate.getDate()) {
        months--;
    }
    const years = Math.floor(months / 12);
    const remainingMonths = months % 12;
    return years > 0 ? `${years} tahun ${remainingMonths} bulan` : `${remainingMonths} bulan`;
};

const calculateCalories = (birthdateStr) => {
    const birthdate = new Date(birthdateStr);
    const now = new Date();
    let months = (now.getFullYear() - birthdate.getFullYear()) * 12 + (now.getMonth() - birthdate.getMonth());
    if (now.getDate() < birthdate.getDate()) {
        months--;
    }
    months = months < 0 ? 0 : months;

    // Calculate base calories based on age in months
    const baseCalories = (() => {
        if (months <= 6) return 600;      // 0-6 months
        if (months <= 12) return 800;     // 7-12 months
        if (months <= 24) return 1000;    // 13-24 months
        if (months <= 36) return 1200;    // 25-36 months
        if (months <= 48) return 1300;    // 37-48 months
        if (months <= 60) return 1400;    // 49-60 months
        if (months <= 84) return 1600;    // 61-84 months
        if (months <= 120) return 1800;   // 85-120 months
        return 2000;                      // >120 months
    })();

    // Adjust for activity level
    const activityMultiplier = 1.2; // Moderate activity
    const calories = baseCalories * activityMultiplier;

    return {
        calories: Math.round(calories),
        recommendations: {
            protein: Math.round(calories * 0.15 / 4),
            karbohidrat: Math.round(calories * 0.55 / 4),
            lemak: Math.round(calories * 0.30 / 9),
        }
    };
};

const childrenWithCalories = computed(() => {
    return props.children.map(child => {
        const { calories, recommendations } = calculateCalories(child.tgl_lahir);

        return {
            ...child,
            kalori: calories,
            rekomendasi: recommendations
        };
    });
});

// Add "All Categories" option to categories
const categoriesWithAll = computed(() => [
    { id_kategori: '', nama_kategori: 'All Categories' },
    ...props.categories
]);

watch([search, selectedCategory], debounce(([newSearch, newCategory]) => {
    const params = {};

    if (newSearch) params.search = newSearch;
    if (newCategory) params.category = newCategory;

    router.get(
        route('nutrition.index'),
        params,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}, 300));

// Nutrition Calculator
const selectedFoods = ref([]);
const quantities = ref({});

// Load saved data from localStorage
const loadSavedData = () => {
    const savedFoods = localStorage.getItem('nutritionCalculatorFoods');
    const savedQuantities = localStorage.getItem('nutritionCalculatorQuantities');

    if (savedFoods) {
        selectedFoods.value = JSON.parse(savedFoods);
    }
    if (savedQuantities) {
        quantities.value = JSON.parse(savedQuantities);
    }
};

// Save data to localStorage
const saveData = () => {
    localStorage.setItem('nutritionCalculatorFoods', JSON.stringify(selectedFoods.value));
    localStorage.setItem('nutritionCalculatorQuantities', JSON.stringify(quantities.value));
};

// Load data when component mounts
loadSavedData();

const addFoodToCalculator = (food) => {
    if (!selectedFoods.value.find(f => f.id_makanan === food.id_makanan)) {
        selectedFoods.value.push(food);
        quantities.value[food.id_makanan] = 100; // Default quantity in grams
        saveData(); // Save after adding
    }
};

const removeFoodFromCalculator = (foodId) => {
    selectedFoods.value = selectedFoods.value.filter(f => f.id_makanan !== foodId);
    delete quantities.value[foodId];
    saveData(); // Save after removing
    if (selectedFoods.value.length === 0) {
        isCalculatorOpen.value = false;
    }
};

// Watch for changes in quantities and save
watch(quantities, () => {
    saveData();
}, { deep: true });

const calculateTotalNutrition = computed(() => {
    return selectedFoods.value.reduce((total, food) => {
        const quantity = quantities.value[food.id_makanan] || 0;
        const multiplier = quantity / 100; // Convert to percentage

        return {
            kalori: total.kalori + (food.kalori * multiplier),
            protein: total.protein + (food.protein * multiplier),
            karbohidrat: total.karbohidrat + (food.karbohidrat * multiplier),
            lemak: total.lemak + (food.lemak * multiplier)
        };
    }, { kalori: 0, protein: 0, karbohidrat: 0, lemak: 0 });
});
</script>

<template>

    <Head :title="$page.component" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-700 dark:text-teal-400 mb-6">Nutrisi</p>

        <!-- Floating Calculator Button -->
        <button @click="isCalculatorOpen = true" style="position: fixed; bottom: 100px;"
            class="w-auto left-1/2 transform -translate-x-1/2 mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-color text-sm">
            Kalkulator Nutrisi ({{ selectedFoods.length }})
        </button>

        <!-- Nutrition Calculator Modal -->
        <div v-if="isCalculatorOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-sm mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Kalkulator Nutrisi</h2>
                    <button @click="isCalculatorOpen = false" class="text-gray-500 hover:text-gray-700">
                        <p class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm">Tutup</p>
                    </button>
                </div>

                <!-- Total Nutrition -->
                <div class="mt-4 mb-4 p-4 bg-teal-100 dark:bg-teal-900 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">Total Nutrisi: ({{
                        selectedFoods.length }} Item)</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="text-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Kalori</span>
                            <p class="text-md font-semibold text-gray-800 dark:text-white">
                                {{ Math.round(calculateTotalNutrition.kalori) }} kcal
                            </p>
                        </div>
                        <div class="text-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Protein</span>
                            <p class="text-md font-semibold text-gray-800 dark:text-white">
                                {{ Math.round(calculateTotalNutrition.protein) }}g
                            </p>
                        </div>
                        <div class="text-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Karbohidrat</span>
                            <p class="text-md font-semibold text-gray-800 dark:text-white">
                                {{ Math.round(calculateTotalNutrition.karbohidrat) }}g
                            </p>
                        </div>
                        <div class="text-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Lemak</span>
                            <p class="text-md font-semibold text-gray-800 dark:text-white">
                                {{ Math.round(calculateTotalNutrition.lemak) }}g
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Selected Foods List (scrollable only this part) -->
                <div v-if="selectedFoods.length > 0" class="mb-4 max-h-[32vh] overflow-y-auto">
                    <div v-for="food in selectedFoods" :key="food.id_makanan"
                        class="flex items-center justify-between gap-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2">
                        <div class="flex-1">
                            <span class="text-gray-800 dark:text-white text-sm">{{ food.nama_makanan }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" v-model="quantities[food.id_makanan]"
                                class="w-20 px-2 py-1 border rounded bg-white dark:bg-gray-600 dark:border-gray-500"
                                min="0" step="1" />
                            <span class="text-gray-600 dark:text-gray-400 w-6 text-center">{{ food.satuan.slice(3)
                            }}</span>
                            <button @click="removeFoodFromCalculator(food.id_makanan)"
                                class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <p v-else class="text-gray-600 dark:text-gray-400 text-center py-4">
                    Pilih makanan dari daftar untuk menghitung total nutrisi
                </p>
            </div>
        </div>

        <!-- Children's Calorie Recommendations -->
        <div v-if="childrenWithCalories.length > 0" class="mb-6">
            <h2 class="text-md font-semibold text-gray-800 dark:text-white mb-2">Rekomendasi Kalori Anak</h2>
            <div class="space-y-3">
                <div v-for="child in childrenWithCalories" :key="child.id_anak"
                    class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-medium text-gray-800 dark:text-white">
                                {{ child.nama }} ({{ getAgeInMonths(child.tgl_lahir) }})
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Kebutuhan Kalori: {{ child.kalori }} kcal
                            </p>
                        </div>
                    </div>
                    <div class="mt-2 grid grid-cols-3 gap-2 text-xs">
                        <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded text-center">
                            <span class="text-gray-600 dark:text-gray-400">Protein:</span>
                            <span class="text-gray-800 dark:text-white ml-1">{{ child.rekomendasi.protein }}g</span>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded text-center">
                            <span class="text-gray-600 dark:text-gray-400">Karbo:</span>
                            <span class="text-gray-800 dark:text-white ml-1">{{ child.rekomendasi.karbohidrat }}g</span>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded text-center">
                            <span class="text-gray-600 dark:text-gray-400">Lemak:</span>
                            <span class="text-gray-800 dark:text-white ml-1">{{ child.rekomendasi.lemak }}g</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Search -->
            <TextInput name="Search" id="search" v-model="search" placeholder="Search foods..." />

            <!-- Category Filter -->
            <Select name="Category" id="category" v-model="selectedCategory" :options="categoriesWithAll"
                optionValue="id_kategori" optionLabel="nama_kategori" />
        </div>

        <!-- Food Grid -->
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div v-for="food in foods" :key="food.id_makanan"
                class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{ food.nama_makanan }} <span
                            class="text-sm text-gray-600 dark:text-gray-400">(per {{ food.satuan }})</span></h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ food.category.nama_kategori }}</p>

                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Kalori:</span>
                            <span class="text-gray-800 dark:text-white">{{ food.kalori }} kcal</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Protein:</span>
                            <span class="text-gray-800 dark:text-white">{{ food.protein }}g</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Karbohidrat:</span>
                            <span class="text-gray-800 dark:text-white">{{ food.karbohidrat }}g</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Lemak:</span>
                            <span class="text-gray-800 dark:text-white">{{ food.lemak }}g</span>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">{{ food.deskripsi }}</p>

                    <!-- Add and remove to Calculator Button -->
                    <div class="flex justify-between gap-2">
                        <button @click="addFoodToCalculator(food)"
                            :disabled="selectedFoods.some(f => f.id_makanan === food.id_makanan)"
                            class="mt-4 text-white px-4 py-2 rounded-lg transition-colors text-sm w-4/5" :class="{
                                'bg-teal-600 hover:bg-teal-700': !selectedFoods.some(f => f.id_makanan === food.id_makanan),
                                'bg-gray-500 cursor-not-allowed': selectedFoods.some(f => f.id_makanan === food.id_makanan)
                            }">
                            Tambah ke Kalkulator
                        </button>
                        <button @click="removeFoodFromCalculator(food.id_makanan)"
                            class="mt-4 text-white px-2 py-2 rounded-lg transition-colors text-sm  w-1/5 flex items-center justify-center" :class="{
                                'bg-red-600 hover:bg-red-800': selectedFoods.some(f => f.id_makanan === food.id_makanan),
                                'bg-gray-500 cursor-not-allowed': !selectedFoods.some(f => f.id_makanan === food.id_makanan)
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
