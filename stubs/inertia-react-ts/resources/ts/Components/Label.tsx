import React from 'react';

interface Props {
    forInput: string;
    value: string;
    className?: string;
}

const Label: React.FC<Props> = ({ forInput, value, className, children }) => {
    return (
        <label htmlFor={forInput} className={`block font-medium text-sm text-gray-700 ` + className}>
            {value ? value : { children }}
        </label>
    );
}

export default Label;
