<script>
    import { onMount } from "svelte";

    export let value = "",
        classes = "";

    let inputElement;

    onMount(() => {
        if (inputElement.hasAttribute("autofocus")) {
            inputElement.focus();
        }
    });
</script>

<input
    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full {classes}"
    bind:value
    bind:this={inputElement}
    {...$$restProps}
/>
