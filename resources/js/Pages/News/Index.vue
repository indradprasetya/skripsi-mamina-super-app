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
    <div class="py-6">
        <p class="text-xl font-bold text-teal-600 dark:text-teal-400 mb-5">Berita dan Informasi Mamina</p>

        <div class="space-y-4">
            <div v-for="news in news" :key="news.id_news"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <Link :href="route('news.show', news.id_news)" class="block">
                <div class="flex p-4 gap-2">
                    <!-- Thumbnail -->
                    <div class="w-32 h-16 flex-shrink-0">
                        <img :src="'/img/news/' + news.thumb" :alt="news.title"
                            class="w-full h-full object-cover rounded">
                    </div>

                    <!-- Content -->
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 line-clamp-2">
                            {{ news.title }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ formatDate(news.date) }}
                        </p>
                    </div>
                </div>
                </Link>
            </div>
        </div>
    </div>
</template>
