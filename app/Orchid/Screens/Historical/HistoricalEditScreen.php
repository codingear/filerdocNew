<?php

namespace App\Orchid\Screens\Historical;
use App\Models\Inquiry;
use App\Models\History;
use App\Models\User;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;

class HistoricalEditScreen extends Screen
{
    public $history;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(History $history): iterable
    {
        $history->load(['user']);
        return [
            'history' => $history
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->history->user->FullName;
    }

    public function description(): ?string
    {
        return 'Edita la información del historíal del paciente';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Volver')
                ->icon('bs.arrow-left')
                ->route('platform.systems.users.edit',$this->history->user->id),

            Button::make('Actualizar')
                ->icon('note')
                ->method('Update')
                ->canSee($this->history->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [

                Layout::rows([
                    Group::make([
                        TextArea::make('history.capacity_suffers')
                            ->rows(5)
                            ->title('¿Qué enfermedades padece en este momento?'),

                        TextArea::make('history.allergy_medicine')
                            ->rows(5)
                            ->title('¿A qué medicamentos es alergico?'),
                    ]),
                    Group::make([
                        TextArea::make('history.family_history')
                            ->rows(5)
                            ->title('Antecedentes heredo familiares'),

                        TextArea::make('history.non_pathological_history')
                            ->rows(5)
                            ->title('Antecedentes personales no patológicos'),

                    ]),
                    Group::make([
                        TextArea::make('history.pathological_history')
                            ->rows(5)
                            ->title('Antecedentes patológicos'),

                        TextArea::make('history.gynecological_history')
                            ->rows(5)
                            ->title('Antecedentes Gineco – Obstétricos'),
                    ]),
                    Group::make([
                        TextArea::make('history.perinatal_history')
                            ->rows(5)
                            ->title('Antecedentes perinatales'),

                        TextArea::make('history.administered_vaccine')
                            ->rows(5)
                            ->title('Historial de vacunas administradas'),
                    ]),

                    Group::make([
                        TextArea::make('history.screening')
                            ->rows(5)
                            ->title('Tamizajes practicados y resultados'),
                    ])
                ])->title('Historial clínico'),
        ];
    }

    /**
     * @param Inquiry $inquiry
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Update(History $history,Request $request)
    {
        $history
            ->fill($request->collect('history')->toArray())
            ->save();
        Alert::info('Se ha actualizado correctamente el historial clínico.');
        return redirect()->route('platform.historical.edit',$this->history->id);
    }
}
