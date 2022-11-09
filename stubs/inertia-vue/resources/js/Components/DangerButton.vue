<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-600 focus:outline-none focus:border-red-300 focus:shadow-outline-red transition ease-in-out duration-150">
        <slot />
    </button>
</template>
