<script>
    export let value = null,
        classes;
</script>

<label
    class="block font-medium text-sm text-gray-700 dark:text-gray-300 {classes}"
    {...$$props}
>
    {#if value}
        <span>{value}</span>
    {:else}
        <span><slot /></span>
    {/if}
</label>
