<x-splade-flash>
    <div v-if="flash.has('status')" v-text="flash.status" {{ $attributes->class('font-medium text-sm text-green-600 dark:text-green-400') }} />
</x-splade-flash>
