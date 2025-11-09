<template>
    <Teleport to="body">
        <Transition name="toast">
            <div v-if="show" :class="toastClasses" class="toast-container" role="alert">
                <div class="toast-icon">
                    <CheckCircle2 v-if="type === 'success'" class="w-5 h-5" />
                    <XCircle v-if="type === 'error'" class="w-5 h-5" />
                    <Info v-if="type === 'info'" class="w-5 h-5" />
                </div>
                <div class="toast-content">
                    <p class="toast-message">{{ message }}</p>
                </div>
                <button @click="close" class="toast-close" aria-label="Close">
                    <X class="w-4 h-4" />
                </button>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue';
import { CheckCircle2, XCircle, Info, X } from 'lucide-vue-next';

interface Props {
    show: boolean;
    message: string;
    type?: 'success' | 'error' | 'info';
    duration?: number;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'success',
    duration: 3000,
});

const emit = defineEmits<{
    (e: 'close'): void;
}>();

let timeout: ReturnType<typeof setTimeout> | null = null;

const toastClasses = computed(() => {
    const baseClasses = 'toast';
    const typeClasses = {
        success: 'toast-success',
        error: 'toast-error',
        info: 'toast-info',
    };
    return `${baseClasses} ${typeClasses[props.type]}`;
});

const close = () => {
    emit('close');
    if (timeout) {
        clearTimeout(timeout);
    }
};

watch(
    () => props.show,
    (newValue) => {
        if (newValue && props.duration > 0) {
            if (timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(() => {
                close();
            }, props.duration);
        }
    },
    { immediate: true }
);
</script>

<style scoped lang="postcss">
.toast-container {
    @apply fixed top-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg min-w-[320px] max-w-md;
}

.toast {
    @apply bg-white border;
}

.toast-success {
    @apply border-green-500 bg-green-50;
}

.toast-error {
    @apply border-red-500 bg-red-50;
}

.toast-info {
    @apply border-blue-500 bg-blue-50;
}

.toast-icon {
    @apply flex-shrink-0;
}

.toast-success .toast-icon {
    @apply text-green-600;
}

.toast-error .toast-icon {
    @apply text-red-600;
}

.toast-info .toast-icon {
    @apply text-blue-600;
}

.toast-content {
    @apply flex-1;
}

.toast-message {
    @apply text-sm font-medium text-gray-900;
}

.toast-close {
    @apply flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors;
}

/* Transition animations */
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
