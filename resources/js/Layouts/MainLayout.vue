<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import BottomBar from '@/Components/BottomBar.vue'
import OfflineNotification from '@/Components/OfflineNotification.vue'

onMounted(() => {
    initFlowbite();
})
</script>

<template>
    <div class="max-w-sm mx-auto mt-4 px-4">
        <OfflineNotification position="top-right" />
        <slot />
        <br><br><br><br><br><br>
        <BottomBar />
    </div>
</template>
