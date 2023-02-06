<script>
    import { onMount, onDestroy } from 'svelte';

    export let show = false;
    export let maxWidth = "2xl";
    export let closeable = true;

    const emit = new CustomEvent(['close']);

    $: if (show) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.overflow = null;
    }

    const close = () => {
        if (closeable) {
            dispatchEvent(emit);
        }
    };

    const closeOnEscape = (e) => {
        if (e.key === 'Escape' && show) {
            close();
        }
    };

    onMount(() => document.addEventListener("keydown", closeOnEscape));

    onDestroy(() => {
        document.removeEventListener("keydown", closeOnEscape);
        document.body.style.overflow = null;
    })

    const maxWidthClass = {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl"
    }[maxWidth]
</script>

{#if show}
<div class="fixed inset-0 z-50 px-4 py-6 overflow-y-auto sm:px-0">
    <button class="fixed inset-0 transition-all transform select-none" on:click={() => close}>
        <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
    </button>

    <div
        class={`mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto ${maxWidthClass}`}
    >
        <slot />
    </div>
</div>
{/if}
