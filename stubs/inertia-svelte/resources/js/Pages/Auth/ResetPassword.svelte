<script>
    import GuestLayout from "../../Layouts/GuestLayout.svelte";
    import InputLabel from "../../Components/InputLabel.svelte";
    import TextInput from "../../Components/TextInput.svelte";
    import InputError from "../../Components/InputError.svelte";
    import PrimaryButton from "../../Components/PrimaryButton.svelte";
    import {route} from '@/route.js';


    import { useForm } from "@inertiajs/svelte";

    export let email, token;

    const form = useForm({
        token: token,
        email: email,
        password: "",
        password_confirmation: "",
    });

    const submit = () => {
        $form.post(route("password.store"), {
            onFinish: () => $form.reset("password", "password_confirmation"),
        });
    };
</script>

<svelte:head>
    <title>Reset Password</title>
</svelte:head>

<GuestLayout>
    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                bind:value={$form.email}
                required
                autofocus
                autocomplete="username"
            />

            <InputError message={$form.errors.email} />
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password" />

            <TextInput
                id="password"
                type="password"
                bind:value={$form.password}
                required
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password} />
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput
                id="password_confirmation"
                type="password"
                bind:value={$form.password_confirmation}
                required
                autocomplete="new-password"
            />

            <InputError message={$form.errors.password_confirmation} />
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton disabled={$form.processing}>
                Reset Password
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
