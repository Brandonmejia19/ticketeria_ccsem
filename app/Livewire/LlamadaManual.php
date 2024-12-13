<?php

namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Actions\Action;

class LlamadaManual extends Component  implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function meetNow(): Action
    {
        return Action::make('meetNow')
            ->color('info')
            ->label('Meet Now')
            ->keyBindings(['command+m', 'ctrl+m'])
            ->extraAttributes(['class' => 'w-full'])
            ->action(fn () => dd('meet'));
    }

    public function schedule(): Action
    {
        return Action::make('schedule')
            ->outlined()
            ->color('gray')
            ->label('Schedule Meeting')
            ->extraAttributes(['class' => 'w-full'])
            ->action(fn () => dd('schedule'));
    }

    public function render(): string
    {
        return <<<'HTML'
            <div class="space-y-2">
                {{ $this->meetNow }}

                {{ $this->schedule }}
            </div>
        HTML;
    }
}
