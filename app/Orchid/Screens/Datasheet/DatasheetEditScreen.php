<?php

namespace App\Orchid\Screens\Datasheet;
use App\Models\Datasheet;
use App\Models\States;
use App\Models\Location;
use App\Models\Municipality;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DatasheetEditScreen extends Screen
{
    public $datasheet;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Datasheet $datasheet): iterable
    {
        $datasheet->load(['user']);
        return [
            'datasheet' => $datasheet,
            'metrics' => [
                'age' => @$datasheet->age,
                'id_patient' => @$datasheet->patient_id,
                'state' => @$datasheet->state->name,
                'socioeconomic' => @$datasheet->socioeconomic
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ficha técnica de '.$this->datasheet->user->FullName;
    }

    public function description(): ?string
    {
        return 'Se muestra toda la información sobre la ficha técnica';
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
                ->route('platform.systems.users.edit',$this->datasheet->user->id),

            Button::make('Guardar')
                ->icon('note')
                ->method('Update')
                ->class('btn btn-success')
                ->canSee($this->datasheet->exists),
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

                Layout::metrics([
                    'Edad'    => 'metrics.age',
                    'ID de paciente' => 'metrics.id_patient',
                    'Estado' => 'metrics.state',
                    'Situación económica' => 'metrics.socioeconomic'
                ]),

                Layout::rows([
                    Group::make([
                        Input::make('datasheet.patient_id')
                            ->title('ID del paciente:'),

                        DateTimer::make('datasheet.birthdate')
                            ->title('Fecha de nacimiento:')
                            ->format('d-m-Y')
                            ->required(),

                        Input::make('datasheet.tutor')
                            ->title('Nombre del Tutor:'),

                    ]),
                    Group::make([
                        Input::make('datasheet.religion')
                            ->title('Religión:'),

                        Input::make('datasheet.socioeconomic')
                            ->title('Situación económica:'),

                        Input::make('datasheet.city')
                            ->title('Ciudad:'),

                    ]),
                    Group::make([
                        Input::make('datasheet.address')
                            ->title('Dirección:'),

                        Input::make('datasheet.cp')
                            ->title('Código postal:'),

                        Input::make('datasheet.gender')
                            ->title('Género:'),

                    ]),
                    Group::make([
                        Input::make('datasheet.blood_type')
                            ->title('Grupo sanguíneo:'),

                        Input::make('datasheet.occupation')
                            ->title('Ocupación:'),

                        Input::make('datasheet.nationality')
                            ->title('Nacionalidad:'),
                    ]),

                    Group::make([
                        Input::make('datasheet.place_of_birth')
                            ->title('Lugar de nacimiento:'),

                        Input::make('datasheet.civil_status')
                            ->title('Estado civil:'),

                        Input::make('datasheet.scholarship')
                            ->title('Nivel de estudios:'),
                    ]),

                    Group::make([
                        Input::make('datasheet.screening')
                            ->title('Tamizaje:'),

                        Input::make('datasheet.scholarship')
                            ->title('Nivel de estudios:'),

                        Select::make('datasheet.different_capacity')
                            ->options([
                                '0'   => 'No',
                                '1' => 'Si',
                            ])->title('Capacidad diferente:'),
                    ]),


                ])->title('Datos de la ficha'),

                Layout::rows([
                    Group::make([
                        Relation::make('datasheet.state_id')
                            ->required()
                            ->fromModel(States::class, 'name')
                            ->title('Selecciona un estado:'),

                        Relation::make('datasheet.municipality_id')
                            ->required()
                            ->fromModel(Municipality::class, 'name')
                            ->title('Selecciona un municipio:'),

                        Relation::make('datasheet.location_id')
                            ->required()
                            ->fromModel(Location::class, 'name')
                            ->title('Selecciona una localidad:'),
                    ]),
                ])->title('Localización'),

                Layout::rows([
                    Group::make([
                        TextArea::make('datasheet.comments')
                            ->rows(5)
                            ->title('Comentarios'),
                    ]),
                ])->title('Información extra'),
        ];
    }

    /*TextArea::make('history.administered_vaccine')
    ->rows(5)
    ->title('Vacuna administrada'),*/

    /**
     * @param Datasheet $datasheet
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function Update(Datasheet $datasheet,Request $request)
    {
        $datasheet
            ->fill($request->collect('datasheet')->toArray())
            ->save();
        Alert::success('Se ha actualizado correctamente la ficha técnica.');
        return redirect()->back();
    }
}
