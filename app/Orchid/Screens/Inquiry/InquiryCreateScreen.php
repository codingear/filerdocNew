<?php

namespace App\Orchid\Screens\Inquiry;
use App\Models\Inquiry;
use App\Models\User;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class InquiryCreateScreen extends Screen
{
    public $user;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query($user_id = null): iterable
    {
        if(!is_null($user_id)){
            $user = User::where('id',$user_id)->first();
            return [
                'user' => $user
            ];
        } else {
            return [
                'user' => null
            ];
        }
        
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Nueva consulta';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'Añadir una consulta nueva';
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
                ->route('platform.systems.users'),

            Button::make('Crear consulta')
                ->icon('note')
                ->method('create')
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
                    Relation::make('inquiry.user_id')
                        ->fromModel(User::class, 'name','id')
                        ->displayAppend('Fullname')
                        ->value(@$this->user->id)
                        ->title('Selecciona el paciente:'),

                    Input::make('inquiry.weight')
                        ->title('Peso:'),

                    Input::make('inquiry.size')
                        ->title('Tamaño:'),
                ]),
                Group::make([
                    Input::make('inquiry.temperature')
                        ->title('Temperatura:'),

                    Input::make('inquiry.sat')
                        ->title('Sat%:'),

                    Input::make('inquiry.fc')
                        ->title('Fc:'),
                ]),
                Group::make([

                    Input::make('inquiry.pc')
                    ->title('Pc:'),

                    Input::make('inquiry.fr')
                        ->title('Fr:'),

                    Input::make('inquiry.dxt')
                        ->title('DXT:'),
                ]),
                Group::make([  
                    Input::make('inquiry.glycemia')
                        ->title('Glicemia:'),

                    Input::make('inquiry.hba1c')
                        ->title('HbA1c:'),

                    Input::make('inquiry.ta')
                        ->title('T.A:'),
                ]),

            ])->title('Información básica'),

            Layout::rows([
                Group::make([
                    Input::make('inquiry.triglycerides')
                        ->title('Triglicéridos:'),

                    Input::make('inquiry.cholesterol')
                        ->title('Colesterol:'),

                    Input::make('inquiry.uric_acid')
                        ->title('Ácido úrico:'),

                ]),
                Group::make([
                    Input::make('inquiry.height_percentile')
                        ->title('Talla percentila:'),

                    Input::make('inquiry.pc_percentile')
                        ->title('Pc percentila:'),
                ]),

                Group::make([

                    TextArea::make('inquiry.reason')
                        ->title('Motivo de la consulta:')
                        ->class('form-control')
                        ->rows(3),

                    TextArea::make('inquiry.medications')
                        ->title('¿Qué medicamentos toma en este momento?:')
                        ->rows(3),
                ]),

            ]),

            Layout::rows([

                Group::make([
                    TextArea::make('inquiry.diagnosis')
                        ->title('Diagnóstico:')
                        ->rows(3),

                    TextArea::make('inquiry.suffering')
                        ->title('Padecimiento actual:')
                        ->rows(3),
                ]),

                Group::make([
                    TextArea::make('inquiry.exploration')
                        ->title('Exploración física:')
                        ->rows(5),

                    TextArea::make('inquiry.cabinet_studies')
                        ->title('Estudios de gabinete:')
                        ->rows(5),
                ]),

                Group::make([
            
                    TextArea::make('inquiry.other_studies')
                        ->title('Otros estudios:')
                        ->rows(5),

                    TextArea::make('inquiry.last_24_hours')
                        ->title('Antecedentes últimas 24 horas:')
                        ->rows(5),
                ]),

                Group::make([
                    TextArea::make('inquiry.patient_notes')
                        ->title('Anotaciones especiales del paciente:')
                        ->rows(5),

                    TextArea::make('inquiry.clinical_signs')
                        ->title('Signos clínicos:')
                        ->rows(5),
                ]),

                Group::make([
                    TextArea::make('inquiry.inherited_family_history')
                        ->title('Antecedentes heredo familiares:')
                        ->rows(5),

                    TextArea::make('inquiry.pathological_history')
                        ->title('Antecedentes patológicos:')
                        ->rows(5),
                ]),
            ])
        ];
    }

    /**
     * @param Inquiry $inquiry
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Inquiry $inquiry, Request $request)
    {
        $inquiry->fill($request->get('inquiry'))->save();
        Alert::info('Se ha creado correctamente la consulta.');
        return redirect()->route('platform.inquiry.edit',$inquiry->id);
    }
}
