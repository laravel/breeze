<script>
    export let value = undefined;
    export let name = undefined;
    export let id = name;

    let input;

    export const focus = () => input.focus();
    export const select = () => input.select();

    $: props = {
        ...$$restProps,
    };

    export const update = (event) => {
        value = event.target.value;
    };
</script>

<input
    type="checkbox"
    {name}
    {value}
    {id}
    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
    bind:this={input}
    on:input={update}
/>
