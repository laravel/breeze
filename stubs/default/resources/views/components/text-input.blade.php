@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:text-gray-500 disabled:dark:text-gray-500 disabled:border-gray-300 disabled:dark:border-gray-700 disabled:bg-gray-50 disabled:dark:bg-gray-900 disabled:cursor-not-allowed']) !!}>
