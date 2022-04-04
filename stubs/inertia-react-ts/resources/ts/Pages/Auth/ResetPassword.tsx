import Button from '../../Components/Button';
import Guest from '../../Layouts/Guest';
import Input from '../../Components/Input';
import Label from '../../Components/Label';
import React, { useEffect } from 'react';
import ValidationErrors from '../../Components/ValidationErrors';
import { useForm } from '@inertiajs/inertia-react';
import route from 'ziggy-js';

interface Props {
    token: string,
    email: string;
}

export default function ResetPassword({ token, email }: Props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const onHandleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setData(event.target.name as "email" | "password" | "password_confirmation" | "token", event.target.value);
    };

    const submit = (e: React.SyntheticEvent) => {
        e.preventDefault();

        post(route('password.update'));
    };

    return (
        <Guest>
            <ValidationErrors errors={errors} />

            <form onSubmit={submit}>
                <div>
                    <Label forInput="email" value="Email" />

                    <Input
                        type="email"
                        name="email"
                        value={data.email}
                        className="block w-full mt-1"
                        autoComplete="username"
                        handleChange={onHandleChange}
                    />
                </div>

                <div className="mt-4">
                    <Label forInput="password" value="Password" />

                    <Input
                        type="password"
                        name="password"
                        value={data.password}
                        className="block w-full mt-1"
                        autoComplete="new-password"
                        isFocused={true}
                        handleChange={onHandleChange}
                    />
                </div>

                <div className="mt-4">
                    <Label forInput="password_confirmation" value="Confirm Password" />

                    <Input
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="block w-full mt-1"
                        autoComplete="new-password"
                        handleChange={onHandleChange}
                    />
                </div>

                <div className="flex items-center justify-end mt-4">
                    <Button className="ml-4 bg-theme-title" processing={processing}>
                        Reset Password
                    </Button>
                </div>
            </form>
        </Guest>
    );
}
