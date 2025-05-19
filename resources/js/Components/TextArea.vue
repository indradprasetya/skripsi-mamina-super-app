<script setup>
const model = defineModel({
    type: null,
    required: true,
})

defineProps({
    name: {
        type: String,
        required: true,
    },
    id: {
        type: String,
        required: true,
    },
    message: String,
    placeholder: String,
})
</script>

<template>
    <div class="mb-4">
        <label :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ name }}</label>
        <textarea :id="id" rows="3" @keydown.enter.prevent
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-teal-500 dark:focus:border-teal-500"
            :class="{ '!ring-1 !ring-red-500 !border-red-500': message, }" :placeholder="placeholder"
            v-model="model"></textarea>
        <small class="text-red-500" v-if="message">{{ message }}</small>
    </div>
</template>
