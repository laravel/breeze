<script>
    export let value = undefined;
    export let name = undefined;
    export let id = name;
    export let autoComplete = undefined;
    export let required = undefined;
    export let type = "text";

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

<div class="flex flex-col items-start">
    <input
        {type}
        {name}
        {value}
        {id}
        class={`border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm ` +
            $$restProps.class}
        {autoComplete}
        {required}
        bind:this={input}
        on:input={update}
    />
</div>
