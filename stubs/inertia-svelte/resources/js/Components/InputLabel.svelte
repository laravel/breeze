<script>
    export let value = undefined;
    let forInput;
    export { forInput as for };
</script>

<label for={forInput} class="block text-sm font-medium text-gray-700 dark:text-gray-300">
    {#if value}
        <span>{value}</span>
    {:else}
        <span><slot /></span>
    {/if}
</label>
