@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50']) !!}>
