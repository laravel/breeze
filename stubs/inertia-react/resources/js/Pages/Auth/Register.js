import React, { useEffect } from "react";
import { useForm } from "@inertiajs/inertia-react";
import { InertiaLink } from "@inertiajs/inertia-react";
import Button from "@/Components/Forms/Button";
import TextInput from "@/Components/Forms/TextInput";
import Guest from "@/Layouts/Guest";
import ValidationErrors from "@/Components/Forms/ValidationErrors";

export default function Register() {

    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
    });

    useEffect(() => {
        return () => {
            reset("password", "password_confirmation");
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
        post(route("register"));
    };

    return (
        <Guest title="Register">
            <ValidationErrors errors={errors} />
            <form onSubmit={submit}>
                <TextInput
                    value={data.name}
                    error={errors.name}
                    type="text"
                    handleChange={onHandleChange}
                    isFocused={true}
                    label="Name"
                    name="name"
                    autoComplete="name"
                />

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
                        value={data.password}
                        error={errors.password}
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
                    <InertiaLink
                        href={route("login")}
                        className="underline text-sm text-gray-600 hover:text-gray-900"
                    >
                        Already registered?
                    </InertiaLink>
                    <div className="ml-4">
                        <Button processing={processing}>
                            Register
                        </Button>
                    </div>
                </div>
            </form>
        </Guest>
    );
}
