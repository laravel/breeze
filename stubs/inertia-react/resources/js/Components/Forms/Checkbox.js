import React from "react";

export default function Checkbox({ name, value, label, handleChange }) {
    return (
        <label className="flex items-center">
            <input
                name={name}
                onChange={(e) => handleChange(e)}
                className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                type="checkbox"
                value={value}
            />
            <span className="ml-2 text-sm text-gray-700">{label}</span>
        </label>
    );
}
