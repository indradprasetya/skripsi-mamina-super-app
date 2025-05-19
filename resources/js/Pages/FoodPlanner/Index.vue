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
        route('food-planner.index'),
        params,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}, 300));
</script>

<template>

    <Head :title="$page.component" />
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-6">Food Planner</p>
        <!-- Children's Calorie Recommendations -->
        <div v-if="childrenWithCalories.length > 0" class="mb-6">
            <h2 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Rekomendasi Kalori Anak</h2>
            <div class="space-y-3">
                <div v-for="child in childrenWithCalories" :key="child.id_anak"
                    class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-medium text-gray-900 dark:text-white">
                                {{ child.nama }} ({{ getAgeInMonths(child.tgl_lahir) }})
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Kebutuhan Kalori: {{ child.kalori }} kcal
                            </p>
                        </div>
                    </div>
                    <div class="mt-2 grid grid-cols-3 gap-2 text-xs">
                        <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded text-center">
                            <span class="text-gray-500 dark:text-gray-400">Protein:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ child.rekomendasi.protein }}g</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded text-center">
                            <span class="text-gray-500 dark:text-gray-400">Karbo:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ child.rekomendasi.karbohidrat }}g</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded text-center">
                            <span class="text-gray-500 dark:text-gray-400">Lemak:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ child.rekomendasi.lemak }}g</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="grid grid-cols-1 sm:grid-cols-2">
            <!-- Search -->
            <TextInput name="Search" id="search" v-model="search" placeholder="Search foods..." />

            <!-- Category Filter -->
            <Select name="Category" id="category" v-model="selectedCategory" :options="categoriesWithAll"
                optionValue="id_kategori" optionLabel="nama_kategori" />
        </div>

        <!-- Food Grid -->
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <div v-for="food in foods" :key="food.id_makanan"
                class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ food.nama_makanan }} <span
                            class="text-sm text-gray-500 dark:text-gray-400">(per {{ food.satuan }})</span></h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ food.category.nama_kategori }}</p>

                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Kalori:</span>
                            <span class="text-gray-900 dark:text-white">{{ food.kalori }} kcal</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Protein:</span>
                            <span class="text-gray-900 dark:text-white">{{ food.protein }}g</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Karbohidrat:</span>
                            <span class="text-gray-900 dark:text-white">{{ food.karbohidrat }}g</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Lemak:</span>
                            <span class="text-gray-900 dark:text-white">{{ food.lemak }}g</span>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ food.deskripsi }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
