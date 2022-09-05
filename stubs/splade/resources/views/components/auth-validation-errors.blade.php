<x-splade-errors>
    <div v-if="Object.keys(errors.all).length" {{ $attributes->class('font-medium text-red-600') }}>
        {{ __('Whoops! Something went wrong.') }}
    </div>
</x-splade-errors>