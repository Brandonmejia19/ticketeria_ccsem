<div x-data="{ open: false }" x-show="open" @open-modal.window="open = true" @close-modal.window="open = false">
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="fixed inset-0 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg">
            <form wire:submit.prevent="save">
                @foreach ($formSchema as $field)
                    {{ $field->render() }}
                @endforeach
                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
            </form>
        </div>
    </div>
</div>
