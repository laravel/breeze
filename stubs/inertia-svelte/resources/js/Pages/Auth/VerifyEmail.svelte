<script>
    import GuestLayout from '@/Layouts/GuestLayout.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import { Link, useForm } from '@inertiajs/svelte';
    import { route } from "@/ziggy";

    export let status;

    const form = useForm({});

    const submit = () => {
        $form.post(route('verification.send'));
    };

    $: verificationLinkSent = (status === "verification-link-sent");
</script>

<svelte:head>
    <title>Email Verification</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
        we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    {#if verificationLinkSent}
    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        A new verification link has been sent to the email address you provided during registration.
    </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div class="mt-4 flex items-center justify-between">
            <PrimaryButton processing={$form.processing}>
                Resend Verification Email
            </PrimaryButton>

            <Link
                href={route("logout")}
                method="post"
                as="button"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
            >
                Log Out
            </Link>
        </div>
    </form>
</GuestLayout>
