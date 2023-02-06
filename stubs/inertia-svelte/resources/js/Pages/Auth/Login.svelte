<script>
    import Checkbox from '@/Components/Checkbox.svelte';
    import GuestLayout from '@/Layouts/GuestLayout.svelte';
    import InputError from '@/Components/InputError.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import { Link, useForm } from '@inertiajs/svelte';
    import { route } from "@/ziggy";

    export let canResetPassword;
    export let status;

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        $form.post(route('login'), {
            onFinish: () => $form.reset('password'),
        });
    };
</script>

<svelte:head>
    <title>Log in</title>
</svelte:head>

<GuestLayout>
    {#if status}
    <div class="mb-4 text-sm font-medium text-green-600">
        {status}
    </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="block w-full mt-1"
                bind:value={$form.email}
                required
                autofocus
                autocomplete="username"
            />

            <InputError class="mt-2" message={$form.errors.email} />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
                id="password"
                type="password"
                class="block w-full mt-1"
                bind:value={$form.password}
                required
                autocomplete="current-password"
            />

            <InputError class="mt-2" message={$form.errors.password} />
        </div>

        <div class="block mt-4">
            <label class="flex items-center" for="remember">
                <Checkbox id="remember" name="remember" bind:value={$form.remember} />
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {#if canResetPassword}
            <Link
                href={route("password.request")}
                class="mr-4 text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
            >
                Forgot your password?
            </Link>
            {/if}

            <PrimaryButton processing={$form.processing}>
                Log in
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>