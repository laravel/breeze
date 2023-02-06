<script>
    export let onClick = undefined;
    export let processing = undefined;
    export let type = "submit";
    let input;
    export const focus = () => input.focus();
    export const select = () => input.select();
    $: props = {
        ...$$restProps,
    };
</script>

<button
    {type}
    class={`inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ${ processing && "opacity-25" }` + $$restProps.class}
    disabled={processing}
    bind:this={input}
    on:click={onClick}
>
    <slot />
</button>
