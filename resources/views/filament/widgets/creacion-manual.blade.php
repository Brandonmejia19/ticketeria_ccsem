<div class="flex flex-wrap gap-4">
    @foreach ($this->getResources() as $resourceClass)
        @php
            $resource = app($resourceClass);
        @endphp

        <button
            wire:click="$emit('openResourceCreateModal', '{{ $resourceClass }}')"
            class="px-4 py-2 text-white bg-blue-500 rounded shadow hover:bg-blue-700"
        >
            Crear {{ $resource::getModelLabel() }}
        </button>
    @endforeach
</div>
