<script setup>

const props = defineProps({
    news: Array
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="$page.component" />
    <div>
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-5">Artikel</p>

        <div class="space-y-4">
            <div v-for="article in news" :key="article.id_news"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <Link :href="route('article.show', article.id_news)" class="block">
                    <div class="flex p-4 gap-2">
                        <!-- Thumbnail -->
                        <div class="w-32 h-24 flex-shrink-0">
                            <img :src="'/storage/' + article.thumb" :alt="article.title" class="w-full h-full object-cover rounded">
                        </div>

                        <!-- Content -->
                        <div class="ml-4 flex-1">
                            <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                {{ article.title }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ formatDate(article.date) }}
                            </p>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>
