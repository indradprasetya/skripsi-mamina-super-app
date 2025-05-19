<script setup>
const model = defineModel({ type: [String, Number], required: true })

defineProps({
    name: {
        type: String,
        required: true,
    },
    id: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
    optionValue: {
        type: String,
        required: true,
    },
    optionLabel: {
        type: String,
        required: true,
    },
    message: String,
    disabled: Boolean,
})
</script>

<template>
    <div class="mb-4">
        <label :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ name }}</label>
        <select :id="id" v-model="model" :disabled="disabled"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-teal-500 dark:focus:border-teal-500"
            :class="{ '!ring-1 !ring-red-500 !border-red-500': message }">
            <option v-for="item in options" :key="item[optionValue]" :value="item[optionValue]">
                {{ item[optionLabel] }}
            </option>
        </select>
        <small class="text-red-500" v-if="message">{{ message }}</small>
    </div>
</template>
