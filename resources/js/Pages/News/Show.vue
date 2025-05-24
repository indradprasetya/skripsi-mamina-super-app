<script setup>
const props = defineProps({
    news: Object
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
    <Head :title="news.title" />
    <div class="py-6">
        <!-- Back Button -->
        <Link :href="route('news.index')"
            class="mb-6 flex items-center text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Berita
        </Link>

        <!-- Article Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                {{ news.title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(news.date) }}
            </p>
        </div>

        <!-- Article Image -->
        <div class="mb-8">
            <img :src="'/img/news/' + news.thumb" :alt="news.title" class="w-full h-[200px] object-cover rounded-lg">
        </div>

        <!-- Article Content -->
        <div class="prose dark:prose-invert max-w-none">
            <div v-html="news.content"></div>
        </div>
    </div>
</template>
