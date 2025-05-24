<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({
    position: { type: String, default: 'bottom-right' } // 'top-right', 'bottom-right', etc
});

const online = ref(navigator.onLine);

const updateStatus = () => {
    online.value = navigator.onLine;
};

onMounted(() => {
    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
});
onUnmounted(() => {
    window.removeEventListener('online', updateStatus);
    window.removeEventListener('offline', updateStatus);
});

const positionClass = computed(() => {
    switch (props.position) {
        case 'top-right': return 'top-4 right-4';
        case 'top-left': return 'top-4 left-4';
        case 'bottom-left': return 'bottom-4 left-4';
        default: return 'bottom-4 right-4';
    }
});
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>

<template>
    <transition name="slide-fade">
        <div v-if="!online" :class="['fixed z-50 p-3 rounded-lg shadow-lg text-white bg-red-600', positionClass]"
            style="min-width:200px;">
            <span class="font-bold">Offline</span> — Anda sedang tidak terhubung internet.
        </div>
    </transition>
</template>