import React from "react";
import { useForm } from "@inertiajs/inertia-react";
import Button from "@/Components/Forms/Button";
import TextInput from "@/Components/Forms/TextInput";
import Guest from "@/Layouts/Guest";
import ValidationErros from "@/Components/Forms/ValidationErrors";

export default function ForgotPassword({ status }) {

    const { data, setData, post, processing, errors } = useForm({
        email: "",
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const submit = (e) => {
        e.preventDefault();
        post(route("password.email"));
    };

    return (
        <Guest title="Forgot Password">
            {status ? (
                <div className="font-medium text-sm py-2 text-center text-green-600">
                    {status}
                </div>
            ) : (
                <>
                    <div className="mb-4 text-sm text-gray-500 leading-normal">
                        Forgot your password? No problem. Just let us know your
                        email address and we will email you a password reset
                        link that will allow you to choose a new one.
                    </div>
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
                        <div className="flex items-center justify-end mt-4">
                            <div className="ml-4">
                                <Button processing={processing}>
                                    Email Password Reset Link
                                </Button>
                            </div>
                        </div>
                    </form>
                </>
            )}
        </Guest>
    );
}
