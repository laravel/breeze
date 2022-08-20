import { usePage } from "@inertiajs/inertia-react";
import React, { useEffect, useRef } from "react";
import InputError from "./InputError";
import Label from "./Label";

export default function Input({
    type = "text",
    label = "",
    name,
    value,
    className,
    autoComplete,
    required,
    isFocused,
    handleChange,
}) {
    const { errors } = usePage().props; // error bag
    const inputError = errors[name];
    const input = useRef();

    useEffect(() => {
        if (isFocused) {
            input.current.focus();
        }
    }, []);

    return (
        <div className="flex flex-col items-start">
            {label && <Label forInput={name} value={label} />}
            <input
                type={type}
                name={name}
                value={value}
                className={`${
                    inputError ? "text-negative-500 border-negative-500" : ""
                } ${className}`}
                ref={input}
                autoComplete={autoComplete}
                required={required}
                onChange={(e) => handleChange(e)}
            />
            {errors && (
                <div className="mt-2">
                    <InputError message={inputError} />
                </div>
            )}
        </div>
    );
}
