import React, { useEffect } from "react";
import { useForm } from "@inertiajs/inertia-react";
import ValidationErros from "@/Components/Forms/ValidationErrors";
import Guest from "@/Layouts/Guest";
import TextInput from "@/Components/Forms/TextInput";
import Button from "@/Components/Forms/Button";

export default function ResetPassword({ token, email }) {

    const { data, setData, post, processing, errors, reset } = useForm({
        token: token,
        email: email,
        password: "",
        password_confirmation: "",
    });

    useEffect(() => {
        return () => {
            reset("password", "password_confirmation");
        };
    }, []);

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route("password.update"));
    };

    return (
        <Guest title="Reset Password">
            <ValidationErros errors={errors} />

            <form onSubmit={submit}>
                <div className="mt-4">
                    <TextInput
                        value={data.email}
                        error={errors.email}
                        type="text"
                        handleChange={onHandleChange}
                        label="Email"
                        name="email"
                        autoComplete="username"
                    />
                </div>
                <div className="mt-4">
                    <TextInput
                        isFocused={true}
                        error={errors.password}
                        value={data.password}
                        type="password"
                        handleChange={onHandleChange}
                        label="Password"
                        name="password"
                        autoComplete="new-password"
                    />
                </div>

                <div className="mt-4">
                    <TextInput
                        value={data.password_confirmation}
                        type="password"
                        handleChange={onHandleChange}
                        label="Password"
                        name="password_confirmation"
                        autoComplete="new-password"
                    />
                </div>

                <div className="flex items-center justify-end mt-4">
                    <div className="ml-4">
                        <Button processing={processing}>
                            Reset Password
                        </Button>
                    </div>
                </div>
            </form>
        </Guest>
    );
}
