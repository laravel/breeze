export default function InputError({ message, className = '' }) {
    return message ? <p className={'text-sm text-red-600 ' + className}>{message}</p> : null;
}
