import React, { useEffect, useRef } from 'react';

interface Props {
    type?: string;
    name: string;
    value: string;
    className?: string;
    autoComplete?: string | undefined;
    required?: boolean;
    isFocused?: boolean;
    handleChange: React.ChangeEventHandler<HTMLInputElement>;
}

const Input: React.FC<Props> = ({
    type = 'text',
    name,
    value,
    className,
    autoComplete,
    required,
    isFocused,
    handleChange,
}) => {
    const input = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (isFocused) {
            input.current?.focus();
        }
    }, []);

    return (
        <div className="flex flex-col items-start">
            <input
                type={type}
                name={name}
                value={value}
                className={
                    `border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm ` +
                    className
                }
                ref={input}
                autoComplete={autoComplete}
                required={required}
                onChange={(e) => handleChange(e)}
            />
        </div>
    );
}

export default Input;
