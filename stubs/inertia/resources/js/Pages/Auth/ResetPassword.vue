<template>
    <breeze-authentication-card>
        <template #logo>
            <inertia-link href="/">
                <breeze-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </inertia-link>
        </template>

        <breeze-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <breeze-label for="email" value="Email" />
                <breeze-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>

            <div class="mt-4">
                <breeze-label for="password" value="Password" />
                <breeze-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <breeze-label for="password_confirmation" value="Confirm Password" />
                <breeze-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <breeze-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reset Password
                </breeze-button>
            </div>
        </form>
    </breeze-authentication-card>
</template>

<script>
    import BreezeApplicationLogo from '@/Components/ApplicationLogo'
    import BreezeAuthenticationCard from '@/Components/AuthenticationCard'
    import BreezeButton from '@/Components/Button'
    import BreezeInput from '@/Components/Input'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        components: {
            BreezeApplicationLogo,
            BreezeAuthenticationCard,
            BreezeButton,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
        },

        props: {
            email: String,
            token: String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    token: this.token,
                    email: this.email,
                    password: '',
                    password_confirmation: '',
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.update'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
