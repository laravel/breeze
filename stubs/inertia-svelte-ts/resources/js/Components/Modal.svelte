<script>
    import { onMount } from "svelte";
    import { fade } from "svelte/transition";

    export let show = false,
        maxWidth = "2xl",
        closeable = true,
        onClose = () => {};

    const close = () => {
        if (closeable) {
            onClose();
        }
    };

    const closeOnEscape = (e) => {
        if (e.key === "Escape" && show) {
            close();
        }
    };

    onMount(() => {
        document.addEventListener("keydown", closeOnEscape);
        return () => {
            document.removeEventListener("keydown", closeOnEscape);
            document.body.style.overflow = null;
        };
    });

    const maxWidthClass = {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
    }[maxWidth];
</script>

{#if show}
    <div
        transition:fade={{ duration: 200 }}
        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
        scroll-region
    >
        {#if show}
            <div
                transition:fade={{ duration: 200 }}
                class="fixed inset-0 transform transition-all"
                on:click={close}
            >
                <div
                    class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"
                />
            </div>
        {/if}

        {#if show}
            <div
                transition:fade={{ duration: 200 }}
                class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto {maxWidthClass}"
            >
                {#if show}
                    <slot />
                {/if}
            </div>
        {/if}
    </div>
{/if}
