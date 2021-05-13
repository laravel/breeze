import React, { useEffect, useRef } from "react";

export default function TextInput({ label, type = 'text', name, value, className, handleChange, autoComplete, required, isFocused }) {

    const input = useRef();

    useEffect(() => {
        if (isFocused) {
            input.current.focus();
        }
    }, []);

    return (
        <div className="flex flex-col items-start">
            {label && <label className="block font-medium text-sm text-gray-700 mb-1">{label}</label>}
            <input
                type={type}
                name={name}
                value={value}
                className={`border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm ` + className}
                ref={input}
                onChange={(e) => handleChange(e)}
                autoComplete={autoComplete}
                required={required}
            />
        </div>
    );
}
