<script setup>
const props = defineProps({
    article: Object
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
    <Head :title="article.title" />
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <Link :href="route('article.index')"
            class="mb-6 flex items-center text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Artikel
        </Link>

        <!-- Article Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                {{ article.title }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ formatDate(article.date) }}
            </p>
        </div>

        <!-- Article Image -->
        <div class="mb-8">
            <img :src="'/storage/' + article.thumb" :alt="article.title" class="w-full h-[400px] object-cover rounded-lg">
        </div>

        <!-- Article Content -->
        <div class="prose dark:prose-invert max-w-none">
            <div v-html="article.content"></div>
        </div>
    </div>
</template>
