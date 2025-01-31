<?php

namespace App\Livewire;

use App\Models\Caso;
use App\Models\Llamadas;
use Livewire\Component;
class LlamadasCreateModal extends Component
{
    public $resourceClass;

    protected $listeners = ['openResourceCreateModal'];

    public function openResourceCreateModal(string $resourceClass)
    {
        $this->resourceClass = $resourceClass;
        $this->dispatchBrowserEvent('open-modal', ['id' => 'llamadas-create-modal']);
    }

    public function render()
    {
        $resource = app($this->resourceClass);

        return view('livewire.llamadas-create-modal', [
            'formSchema' => $resource::getFormSchema(),
        ]);
    }
}
