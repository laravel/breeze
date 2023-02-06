<script>
    export let message = undefined;
    let clazz;
    export { clazz as class };
</script>

{#if message}
    <p class="text-sm text-red-600 dark:text-red-400 {clazz}">
        {message}
    </p>
{/if}
