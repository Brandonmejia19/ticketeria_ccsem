<div class="p-4 bg-white rounded-lg shadow">
    <h2 class="text-lg font-semibold">Información de la llamada</h2>
    <div class="mt-2 p-2 bg-gray-100 rounded">
        <p><strong>Duración:</strong> {{ $duration }}</p>
        <p><strong>Extensión:</strong> {{ $extension }}</p>
    </div>

    <div class="mt-4">
        <input type="text" placeholder="No. Alertante" class="w-full p-2 mb-2 border rounded" />
        <select class="w-full p-2 mb-4 border rounded">
            <option>Tipo de Atención</option>
            <!-- Más opciones aquí -->
        </select>

        <button class="w-full p-2 mb-2 bg-gray-300 rounded">Atención PH</button>
        <button class="w-full p-2 mb-2 bg-gray-300 rounded">Traslado</button>
        <button class="w-full p-2 bg-gray-300 rounded">Informativa</button>
    </div>
</div>
