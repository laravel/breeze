<script>
    export let message = null;
</script>

{#if message}
    <p class="text-sm text-red-600 dark:text-red-400 mt-2">
        {message}
    </p>
{/if}
