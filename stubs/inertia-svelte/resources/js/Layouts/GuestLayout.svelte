<script>
    import { Link } from "@inertiajs/svelte";
    import ApplicationLogo from "../Components/ApplicationLogo.svelte";
</script>

<div
    class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900"
>
    <div>
        <Link href="/">
            <ApplicationLogo classes="w-20 h-20 fill-current text-gray-500" />
        </Link>
    </div>

    <div
        class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
    >
        <slot />
    </div>
</div>
