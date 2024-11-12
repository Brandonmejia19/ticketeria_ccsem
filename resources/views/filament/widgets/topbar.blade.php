{{-- resources/views/layouts/topbar.blade.php --}}

<ul class="flex space-x-4">
    <x-topbar-item url="/home" icon="heroicon-o-home" activeIcon="heroicon-s-home" active="{{ request()->is('home') }}">
        Inicio
    </x-topbar-item>

    <x-topbar-item url="/notifications" icon="heroicon-o-bell" badge="5" badgeColor="danger">
        Notificaciones
    </x-topbar-item>

    <x-topbar-item url="/settings" icon="heroicon-o-cog">
        Configuración
    </x-topbar-item>
</ul>
