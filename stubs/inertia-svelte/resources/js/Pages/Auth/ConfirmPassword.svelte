<script>
    import { useForm } from "@inertiajs/svelte";
    import {route} from '@/route.js';


    import InputLabel from "../../Components/InputLabel.svelte";
    import TextInput from "../../Components/TextInput.svelte";
    import InputError from "../../Components/InputError.svelte";
    import PrimaryButton from "../../Components/PrimaryButton.svelte";

    const form = useForm({
        password: "",
    });

    const submit = () => {
        $form.post(route("password.confirm"), {
            onFinish: () => $form.reset(),
        });
    };
</script>

<svelte:head>
    <title>Confirm Password</title>
</svelte:head>

<div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
    This is a secure area of the application. Please confirm your password
    before continuing.
</div>

<form on:submit|preventDefault={submit}>
    <div>
        <InputLabel for="password" value="Password" />
        <TextInput
            id="password"
            type="password"
            classes="mt-1 block w-full"
            bind:value={$form.password}
            required
            autocomplete="current-password"
            autofocus
        />
        <InputError classes="mt-2" message={$form.errors.password} />
    </div>

    <div class="flex justify-end mt-4">
        <PrimaryButton disabled={$form.processing}>Confirm</PrimaryButton>
    </div>
</form>
