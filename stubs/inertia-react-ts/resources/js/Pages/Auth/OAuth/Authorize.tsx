import { FormEventHandler } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import PrimaryButton from '@/Components/PrimaryButton';
import SecondaryButton from '@/Components/SecondaryButton';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Authorize({
    user,
    client,
    scopes,
    authToken,
}: {
    user: { name: string; email: string },
    client: { id: string; name: string },
    scopes: { description: string }[],
    authToken: string,
}) {
    const { post, processing, transform } = useForm({
        state: route().params.state,
        client_id: client.id,
        auth_token: authToken,
    });

    const approve: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('passport.authorizations.approve'));
    };

    const deny: FormEventHandler = (e) => {
        e.preventDefault();

        transform((data) => ({
            ...data,
            _method: 'delete',
        }));

        post(route('passport.authorizations.deny'));
    };

    return (
        <GuestLayout>
            <Head title="Authorization Request" />

            <div className="mb-4 text-gray-600 text-center">
                <p>
                    <strong>{user.name}</strong>
                </p>
                <p className="text-sm">{user.email}</p>
            </div>

            <div className="mb-4 text-sm text-gray-600">
                <strong>{client.name}</strong> is requesting permission to access your account.
            </div>

            {scopes.length > 0 && (
                <div className="mb-4 text-sm text-gray-600">
                    <p className="pb-1">This application will be able to:</p>

                    <ul className="list-inside list-disc">
                        {scopes.map((scope) => (
                            <li>{scope.description}</li>
                        ))}
                    </ul>
                </div>
            )}

            <div className="flex flex-row-reverse gap-3 mt-4 flex-wrap items-center">
                <form onSubmit={approve}>
                    <PrimaryButton disabled={processing}>Authorize</PrimaryButton>
                </form>

                <form onSubmit={deny}>
                    <SecondaryButton type="submit" disabled={processing}>
                        Decline
                    </SecondaryButton>
                </form>

                <Link
                    href={route('passport.authorizations.authorize', { ...route().params, prompt: 'login' })}
                    className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Log into another account
                </Link>
            </div>
        </GuestLayout>
    );
}
