import React, { useEffect } from "react";
import { useForm } from "@inertiajs/inertia-react";
import { InertiaLink } from "@inertiajs/inertia-react";
import Checkbox from "@/Components/Forms/Checkbox";
import Button from "@/Components/Forms/Button";
import TextInput from "@/Components/Forms/TextInput";
import Guest from "@/Layouts/Guest";
import ValidationErros from "@/Components/Forms/ValidationErrors";

export default function Login({ status, canResetPassword }) {

    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        password: "",
        remember: "",
    });

    useEffect(() => {
        return () => {
            reset("password");
        };
    }, []);

    const onHandleChange = (event) => {
        if (event.target.type === "checkbox") {
            setData(event.target.name, event.target.checked);
        } else {
            setData(event.target.name, event.target.value);
        }
    };

    const submit = (e) => {
        e.preventDefault();
        post(route("login"));
    };

    return (
        <Guest title="Log in">
            {status && (
                <div className="font-medium text-sm py-2 text-center text-green-600">
                    {status}
                </div>
            )}
            <ValidationErros errors={errors} />
            <form onSubmit={submit}>
                <TextInput
                    value={data.email}
                    error={errors.email}
                    type="text"
                    handleChange={onHandleChange}
                    isFocused={true}
                    label="Email"
                    name="email"
                />

                <div className="mt-4">
                    <TextInput
                        value={data.password}
                        error={errors.password}
                        type="password"
                        handleChange={onHandleChange}
                        label="Password"
                        name="password"
                    />
                </div>

                <div className="block mt-4">
                    <Checkbox
                        name="remember"
                        value={data.remember}
                        handleChange={onHandleChange}
                        label="Remember me"
                    />
                </div>

                <div className="flex items-center justify-end mt-4">
                    {canResetPassword && (
                        <InertiaLink
                            href={route("password.request")}
                            className="underline text-sm text-gray-600 hover:text-gray-900"
                        >
                            Forgot your password?
                        </InertiaLink>
                    )}
                    <div className="ml-4">
                        <Button processing={processing}>
                            Log in
                        </Button>
                    </div>
                </div>
            </form>
        </Guest>
    );
}
