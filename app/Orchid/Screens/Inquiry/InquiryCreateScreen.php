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
        return $this->user->FullName;
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

            Button::make('Guardar')
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
                Input::make('inquiry.user_id')
                ->value(@$this->user->id)
                ->type('hidden'),

                Group::make([

                    Input::make('inquiry.weight')
                        ->title('Peso:'),

                    Input::make('inquiry.size')
                        ->title('Talla:'),

                    Input::make('inquiry.temperature')
                        ->title('Temperatura:'),

                    Input::make('inquiry.pc')
                        ->title('Pc:'),
                ]),
                Group::make([
                    Input::make('inquiry.sat')
                        ->title('Sat%:'),

                    Input::make('inquiry.fc')
                        ->title('Fc.:'),

                    Input::make('inquiry.fr')
                        ->title('Fr.:'),

                    Input::make('inquiry.ta')
                        ->title('TA:'),
                ]),
                Group::make([
                    Input::make('inquiry.dxt')
                        ->title('DXT:'),
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

                Group::make([
                    TextArea::make('inquiry.suffering')
                        ->title('Padecimiento actual:')
                        ->rows(3),

                    TextArea::make('inquiry.exploration')
                        ->title('Exploración física:')
                        ->rows(3),
                ]),

                Group::make([
                    TextArea::make('inquiry.cabinet_studies')
                        ->title('Estudios de gabinete:')
                        ->rows(3),

                    TextArea::make('inquiry.other_studies')
                        ->title('Otros estudios:')
                        ->rows(3),
                ]),

                Group::make([
                    TextArea::make('inquiry.diagnosis')
                        ->title('Diagnóstico:')
                        ->rows(5),
                ]),

            ])->title('Nota médica'),

            Layout::rows([
               
                Group::make([
                    Input::make('inquiry.glycemia')
                        ->title('Glicemia:'),

                    Input::make('inquiry.hba1c')
                        ->title('HbA1c:'),

                    Input::make('inquiry.triglycerides')
                        ->title('Triglicéridos:'),

                    Input::make('inquiry.cholesterol')
                        ->title('Colesterol:'),
                ]),

                Group::make([
                    Input::make('inquiry.uric_acid')
                        ->title('Ácido úrico:'),
                ]),

            ])->title('Cronometría de seguimiento clínico'),

            Layout::rows([
                Group::make([
                    Input::make('inquiry.height_percentile')
                        ->title('Talla percentila:'),

                    Input::make('inquiry.pc_percentile')
                        ->title('Pc percentila:'),
                ]),
            ])->title('Percentiles de crecimiento infantil'),

            Layout::rows([
                Group::make([
                    TextArea::make('inquiry.patient_notes')
                    ->title('Anotaciones especiales del paciente:')
                    ->rows(7),
                ]),
            ])->title('Anotaciones especiales del paciente'),

            Layout::rows([
                Group::make([
                    TextArea::make('inquiry.treatment')
                    ->title('Tratamiento:')
                    ->rows(7),
                ]),
            ])->title('Plan y tratamiento del paciente'),
           
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
