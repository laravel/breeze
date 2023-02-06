<script>
    import GuestLayout from '@/Layouts/GuestLayout.svelte';
    import InputError from '@/Components/InputError.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import { useForm } from '@inertiajs/svelte';
    import { route } from "@/ziggy";

    export let status;

    const form = useForm({
        email: '',
    });

    const submit = () => {
        $form.post(route('password.email'));
    };
</script>

<svelte:head>
    <title>Forgot Password</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset
        link that will allow you to choose a new one.
    </div>

    {#if status}
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        {status}
    </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                bind:value={$form.email}
                required
                autofocus
                autocomplete="username"
            />

            <InputError class="mt-2" message={$form.errors.email} />
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton processing={$form.processing}>
                Email Password Reset Link
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
