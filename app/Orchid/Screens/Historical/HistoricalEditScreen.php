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
        return 'Historial clínico de '.$this->history->user->FullName;
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
                            ->title('Padecimiento actual'),

                        TextArea::make('history.allergy_medicine')
                            ->rows(5)
                            ->title('Alergia a medicamentos'),
                    ]),
                    Group::make([
                        TextArea::make('history.family_history')
                            ->rows(5)
                            ->title('Historia familiar'),

                        TextArea::make('history.non_pathological_history')
                            ->rows(5)
                            ->title('Antecedentes no-patólogicos'),

                    ]),
                    Group::make([
                        TextArea::make('history.pathological_history')
                            ->rows(5)
                            ->title('Antecedentes patológicos'),

                        TextArea::make('history.gynecological_history')
                            ->rows(5)
                            ->title('Antecedentes ginecológicos'),
                    ]),
                    Group::make([
                        TextArea::make('history.perinatal_history')
                            ->rows(5)
                            ->title('Antecedentes perinatales'),

                        TextArea::make('history.administered_vaccine')
                            ->rows(5)
                            ->title('Vacuna administrada'),
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
    public function Update(Request $request)
    {
        $histo = History::where('user_id',$this->history->user->id)->first();
        $histo->capacity_suffers = $request->history['capacity_suffers'];
        $histo->allergy_medicine = $request->history['allergy_medicine'];
        $histo->family_history = $request->history['family_history'];
        $histo->non_pathological_history = $request->history['non_pathological_history'];
        $histo->pathological_history = $request->history['pathological_history'];
        $histo->gynecological_history = $request->history['gynecological_history'];
        $histo->perinatal_history = $request->history['perinatal_history'];
        $histo->administered_vaccine = $request->history['administered_vaccine'];
        $histo->save();

        Alert::info('Se ha actualizado correctamente el historial clínico.');
        return redirect()->route('platform.historical.edit',$this->history->user->id);
    }
}
