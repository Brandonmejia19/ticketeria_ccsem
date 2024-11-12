<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-semibold ">Información de la llamada</h2>
        <div class="mt-2 p-2 rounded ">
            <p><strong>Duración:</strong> {{ $duration }}</p>
            <p><strong>Extensión:</strong> {{ $extension }}</p>
        </div>
    </x-filament::section>

    <br>
    <x-filament::section>

        <div class="mt-4">
            <input type="text" placeholder="No. Alertante" class="w-full p-2 mb-2 border rounded" />
            <h2 placeholder="Tipo de Atención" class="w-full p-2 mb-2 border rounded">
                <center>Tipo de Atención</center>
            </h2>
            <button class="w-full p-2 mb-2 bg-gray-300 rounded">Atención PH</button>
            <button class="w-full p-2 mb-2 bg-gray-300 rounded">Traslado</button>
            <button class="w-full p-2 bg-gray-300 rounded">Informativa</button>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
