<?php
use Carbon\Carbon;

//DB OLD
use App\Models\Usuario;
use App\Models\Estados;
use App\Models\Localidades;
use App\Models\Municipios;

use App\Models\Identificacion;
use App\Models\Inquiry;
use App\Models\Location;
use App\Models\Municipality;
use App\Models\States;
use Illuminate\Support\Facades\Route;

//DB
use App\Models\User;
use App\Models\Datasheet;
use App\Models\History;
use Illuminate\Support\Str;
use Orchid\Platform\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/add-roles', function () {

    /**
     * Command for create Super Admin
     * php artisan orchid:admin SuperAdmin webmaster@filerdoc.com Meg@blaster007@7251
     * User: webmaster@filerdoc.com
     * Password: Meg@blaster007@7251
     **/

    $role = new Role();
    $role->name = 'Administrator';
    $role->slug = 'administrator';
    $role->save();

    $role = new Role();
    $role->name = 'Doctor';
    $role->slug = 'doctor';
    $role->save();

    $role = new Role();
    $role->name = 'Patient';
    $role->slug = 'patient';
    $role->save();

    //Get role
    $role_admin = Role::where('slug','administrator')->first();

    //Create user
    $user = new User();
    $user->name = 'Braulio';
    $user->email = 'codingear@gmail.com';
    $user->last_name = 'Miramontes';
    $user->mother_last_name = 'Valdivia';
    $user->phone = '333317237156';
    $user->password = Hash::make('Meg@blaster007@7251');
    $user->save();

    //Add role
    $user->addRole($role_admin);

    //Add extra info
    $history = new History();
    $history->user_id = $user->id;
    $history->save();

    $datasheet = new Datasheet();
    $datasheet->user_id = $user->id;
    $datasheet->save();

});

Route::get('/asign-role/{role}/{user_id}',function($name_role,$user_id){

    $user = User::find($user_id);
    $role = Role::where('slug',$name_role)->first();
    //Add asign role
    $user->addRole($role);

});

Route::get('/import/user/{id}', function(string $id) {

    //php artisan orchid:admin Braulio codingear@gmail.com Meg@blaster007@7251

     //Get all doctors
     $user1 = 558;//Agustín Gutiérrez
     $user2 = 568;//Fernando Solis
     $user3 = 397;//Susana Valadez Campos
     $user4 = 461;//Maria Asunción
     $user5 = 382;//Delfino Castillo
     $user6 = 404;//Chantal

    $doctor = Usuario::where('rol','Médico')->where('id',$id)->first();
    if($doctor){
        $doctorExist = User::where('email', $doctor->usuario)->first();

        if(!$doctorExist){

            //Create user doctor
            $user = new User();
            $user->name = $doctor->nombre;
            $user->last_name = $doctor->apellido;
            $user->email = $doctor->usuario;
            $user->alias = $doctor->alias;
            $user->locked = $doctor->bloqueado;
            $user->password = Hash::make('password');
            $user->save();

            //Add Role
            $role_doctor = Role::where('slug','doctor')->first();
            $user->addRole($role_doctor);

            //Add history
            $history = new History();
            $history->user_id = $user->id;
            $history->save();

            //Add Datasheet
            $datasheet = new Datasheet();
            $datasheet->user_id = $user->id;
            $datasheet->save();

            $pacientes = Identificacion::where('medico',$doctor->id)->get();

            //Insertar citas
            foreach($pacientes as $paciente){

                $random = Str::random(10);

                //Check if user exist
                if($paciente->correo || $paciente->nombre){

                    $nameExist = User::where('name', $paciente->nombre)->first();
                    $emailExist = User::where('email', $paciente->correo)->first();

                    if($emailExist || $nameExist){

                        $patient = new Inquiry;
                        //$patient->name = ucwords(strtolower($paciente->nombre));
                        $patient->user_id = (!empty($emailExist->id))? $emailExist->id: $nameExist->id;
                        $patient->doctor_id = $user->id;
                        $patient->created_at = Carbon::parse($paciente->creado);
                    
                        foreach($paciente->citas as $cita){

                            if($cita->res_direct->pregunta == 'Peso'){
                                $patient->weight = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Talla'){
                                $patient->size = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Temperatura'){
                                $patient->temperature = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Sat%'){
                                $patient->sat = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Fc.'){
                                $patient->fc = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Pc'){
                                $patient->pc = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Fr.'){
                                $patient->fr = ucwords(strtolower($cita->respuesta));
                            }
                            
                            if($cita->res_direct->pregunta == 'Glicemia'){
                                $patient->glycemia = ucwords(strtolower($cita->respuesta));
                            }   
                            
                            if($cita->res_direct->pregunta == 'HbA1c'){
                                $patient->hba1c = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'T.A.'){
                                $patient->ta = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Triglicéridos'){
                                $patient->triglycerides = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Colesterol'){
                                $patient->cholesterol = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Acido úrico'){
                                $patient->uric_acid = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Anotaciones especiales del paciente'){
                                $patient->patient_notes = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Signos clínicos'){
                                $patient->clinical_signs = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Antecedentes heredo familiares'){
                                $patient->inherited_family_history = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Antecedentes patológicos'){
                                $patient->pathological_history = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'ANTECEDENTES ÚLTIMAS 24 HORAS'){
                                $patient->last_24_hours = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'DXT'){
                                $patient->dxt = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Edad'){
                                $patient->age = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Peso percentila'){
                                $patient->height_percentile = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Pc percentila'){
                                $patient->pc_percentile = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Motivo de la consulta'){
                                $patient->reason = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Diagnóstico'){
                                $patient->diagnosis = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Padecimiento actual'){
                                $patient->suffering = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == '¿Qué medicamentos toma en este momento?'){
                                $patient->medications = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Exploración física'){
                                $patient->exploration = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Estudios de gabinete'){
                                $patient->cabinet_studies = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Otros estudios'){
                                $patient->other_studies = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Plan y tratamiento del paciente'){
                                $patient->treatment = ucwords(strtolower($cita->respuesta_larga));
                            }
                        }

                        $patient->save();

                    } else {

                        //Create patients doctor
                        $patient = new User();
                        $patient->name = ucwords(strtolower($paciente->nombre));
                        $patient->phone = ucwords(strtolower($paciente->telefono));
                        $patient->email = (!empty($paciente->correo))? strtolower($paciente->correo) : strtolower($random).'@nomail.com';
                        $patient->last_name = ucwords(strtolower($paciente->apellido_paterno));
                        $patient->mother_last_name = ucwords(strtolower($paciente->apellido_materno));
                        $patient->doctor_id = $user->id;
                        $patient->photo = (!empty($paciente->fotografia))? $paciente->fotografia:'default.png';
                        $patient->password = Hash::make('password');
                        $patient->save();

                        //Add Role
                        $role_patient = Role::where('slug','patient')->first();
                        $patient->addRole($role_patient);

                        $history = new History();
                        $history->capacity_suffers = ucwords(strtolower($paciente->enfermedad_padece));
                        $history->allergy_medicine = ucwords(strtolower($paciente->medicamento_alergico));
                        $history->family_history = ucwords(strtolower($paciente->antecedente_familiar));
                        $history->non_pathological_history = ucwords(strtolower($paciente->antecedente_nopatologico));
                        $history->pathological_history = ucwords(strtolower($paciente->antecedente_patologico));
                        $history->gynecological_history = ucwords(strtolower($paciente->antecedente_gineco));
                        $history->perinatal_history = ucwords(strtolower($paciente->antecedente_perinatal));
                        $history->administered_vaccine = ucwords(strtolower($paciente->vacuna_administrada));
                        //$history->archived = 
                        $history->user_id = $patient->id;
                        $history->save();

                        //Random Number
                        $randomNumber = rand(1000,10000);

                        $datasheet = new Datasheet();
                        $datasheet->patient_id = $randomNumber;
                        $datasheet->religion = $paciente->religion;
                        $datasheet->tutor = ucwords(strtolower($paciente->tutor));
                        $datasheet->socioeconomic = $paciente->socio_economico;
                        $datasheet->city = $paciente->ciudad;
                        $datasheet->address = ucwords(strtolower($paciente->direccion));
                        $datasheet->cp = $paciente->cp;
                        $datasheet->gender = $paciente->sexo;
                        $datasheet->blood_type = $paciente->grupo_sanguineo;
                        $datasheet->nationality = $paciente->nacionalidad;
                        $datasheet->place_of_birth = $paciente->lugar_de_nacimiento;
                        $datasheet->civil_status = $paciente->estado_civil;
                        $datasheet->scholarship = $paciente->escolaridad;
                        $datasheet->birthdate = $paciente->fecha_nacimiento;
                        $datasheet->different_capacity = ($paciente->capacidad_diferente == 'NO')? '0':'1';
                        $datasheet->screening = ucwords(strtolower($paciente->tamizaje));
                        $datasheet->state_id = (!empty($paciente->estado)? $paciente->estado:'1');;
                        $datasheet->municipality_id = (!empty($paciente->municipio)? $paciente->municipio:'1');
                        $datasheet->location_id = (!empty($paciente->localidad)? $paciente->localidad:'1');
                        $datasheet->occupation = $paciente->ocupacion;
                        $datasheet->user_id = $patient->id;
                        $datasheet->save();

                        if($paciente->citas->count() > 0){

                            $inquiry = new Inquiry;
                            //$inquiry->name = ucwords(strtolower($paciente->nombre));
                            $inquiry->user_id = $patient->id;
                            $inquiry->doctor_id = $user->id;
                            $inquiry->created_at = Carbon::parse($paciente->creado);
                            
                            foreach($paciente->citas as $cita){

                                if($cita->res_direct->pregunta == 'Peso'){
                                    $inquiry->weight = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Talla'){
                                    $inquiry->size = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Temperatura'){
                                    $inquiry->temperature = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Sat%'){
                                    $inquiry->sat = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Fc.'){
                                    $inquiry->fc = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Pc'){
                                    $inquiry->pc = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Fr.'){
                                    $inquiry->fr = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Edad'){
                                    $inquiry->age = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Peso percentila'){
                                    $inquiry->height_percentile = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Pc percentila'){
                                    $inquiry->pc_percentile = ucwords(strtolower($cita->respuesta));
                                }

                                if($cita->res_direct->pregunta == 'Motivo de la consulta'){
                                    $inquiry->reason = ucwords(strtolower($cita->respuesta_larga));
                                }

                                if($cita->res_direct->pregunta == 'Diagnóstico'){
                                    $inquiry->diagnosis = ucwords(strtolower($cita->respuesta_larga));
                                }

                                if($cita->res_direct->pregunta == 'Padecimiento actual'){
                                    $inquiry->suffering = ucwords(strtolower($cita->respuesta_larga));
                                }

                                if($cita->res_direct->pregunta == '¿Qué medicamentos toma en este momento?'){
                                    $inquiry->medications = ucwords(strtolower($cita->respuesta_larga));
                                }

                                if($cita->res_direct->pregunta == 'Exploración física'){
                                    $inquiry->exploration = ucwords(strtolower($cita->respuesta_larga));
                                }

                                if($cita->res_direct->pregunta == 'Estudios de gabinete'){
                                    $inquiry->cabinet_studies = ucwords(strtolower($cita->respuesta_larga));
                                }

                                if($cita->res_direct->pregunta == 'Otros estudios'){
                                    $inquiry->other_studies = ucwords(strtolower($cita->respuesta_larga));
                                }

                                if($cita->res_direct->pregunta == 'Plan y tratamiento del paciente'){
                                    $inquiry->treatment = ucwords(strtolower($cita->respuesta_larga));
                                }

                            }

                            $inquiry->save();
                        }
                    }

                } else {

                    //Create patients doctor
                    $patient = new User();
                    $patient->name = ucwords(strtolower($paciente->nombre));
                    $patient->phone = ucwords(strtolower($paciente->telefono));
                    $patient->email = (!empty($paciente->correo))? strtolower($paciente->correo) : strtolower($random).'@nomail.com';
                    $patient->last_name = ucwords(strtolower($paciente->apellido_paterno));
                    $patient->mother_last_name = ucwords(strtolower($paciente->apellido_materno));
                    $patient->doctor_id = $user->id;
                    $patient->photo = (!empty($paciente->fotografia))? $paciente->fotografia:'default.png';
                    $patient->password = Hash::make('password');
                    $patient->save();

                    //Add Role
                    $role_patient = Role::where('slug','patient')->first();
                    $patient->addRole($role_patient);
                    
                    $history = new History();
                    $history->capacity_suffers = ucwords(strtolower($paciente->enfermedad_padece));
                    $history->allergy_medicine = ucwords(strtolower($paciente->medicamento_alergico));
                    $history->family_history = ucwords(strtolower($paciente->antecedente_familiar));
                    $history->non_pathological_history = ucwords(strtolower($paciente->antecedente_nopatologico));
                    $history->pathological_history = ucwords(strtolower($paciente->antecedente_patologico));
                    $history->gynecological_history = ucwords(strtolower($paciente->antecedente_gineco));
                    $history->perinatal_history = ucwords(strtolower($paciente->antecedente_perinatal));
                    $history->administered_vaccine = ucwords(strtolower($paciente->vacuna_administrada));
                    //$history->archived = 
                    $history->user_id = $patient->id;
                    $history->save();

                    //Random Number
                    $randomNumber = rand(1000,10000);

                    $datasheet = new Datasheet();
                    $datasheet->patient_id = $randomNumber;
                    $datasheet->religion = $paciente->religion;
                    $datasheet->tutor = ucwords(strtolower($paciente->tutor));
                    $datasheet->socioeconomic = $paciente->socio_economico;
                    $datasheet->city = $paciente->ciudad;
                    $datasheet->address = ucwords(strtolower($paciente->direccion));
                    $datasheet->cp = $paciente->cp;
                    $datasheet->gender = $paciente->sexo;
                    $datasheet->blood_type = $paciente->grupo_sanguineo;
                    $datasheet->nationality = $paciente->nacionalidad;
                    $datasheet->place_of_birth = $paciente->lugar_de_nacimiento;
                    $datasheet->civil_status = $paciente->estado_civil;
                    $datasheet->scholarship = $paciente->escolaridad;
                    $datasheet->birthdate = $paciente->fecha_nacimiento;
                    $datasheet->different_capacity = ($paciente->capacidad_diferente == 'NO')? '0':'1';
                    $datasheet->screening = ucwords(strtolower($paciente->tamizaje));
                    $datasheet->state_id = (!empty($paciente->estado)? $paciente->estado:'1');;
                    $datasheet->municipality_id = (!empty($paciente->municipio)? $paciente->municipio:'1');
                    $datasheet->location_id = (!empty($paciente->localidad)? $paciente->localidad:'1');
                    $datasheet->occupation = $paciente->ocupacion;
                    $datasheet->user_id = $patient->id;
                    $datasheet->save();

                    if($paciente->citas->count() > 0){

                        $inquiry = new Inquiry;
                        $inquiry->user_id = $patient->id;
                        $inquiry->doctor_id = $user->id;
                        $inquiry->created_at = Carbon::parse($paciente->creado);
                        
                        foreach($paciente->citas as $cita){

                            if($cita->res_direct->pregunta == 'Peso'){
                                $inquiry->weight = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Talla'){
                                $inquiry->size = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Temperatura'){
                                $inquiry->temperature = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Sat%'){
                                $inquiry->sat = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Fc.'){
                                $inquiry->fc = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Pc'){
                                $inquiry->pc = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Fr.'){
                                $inquiry->fr = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Edad'){
                                $inquiry->age = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Peso percentila'){
                                $inquiry->height_percentile = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Pc percentila'){
                                $inquiry->pc_percentile = ucwords(strtolower($cita->respuesta));
                            }

                            if($cita->res_direct->pregunta == 'Motivo de la consulta'){
                                $inquiry->reason = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Diagnóstico'){
                                $inquiry->diagnosis = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Padecimiento actual'){
                                $inquiry->suffering = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == '¿Qué medicamentos toma en este momento?'){
                                $inquiry->medications = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Exploración física'){
                                $inquiry->exploration = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Estudios de gabinete'){
                                $inquiry->cabinet_studies = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Otros estudios'){
                                $inquiry->other_studies = ucwords(strtolower($cita->respuesta_larga));
                            }

                            if($cita->res_direct->pregunta == 'Plan y tratamiento del paciente'){
                                $inquiry->treatment = ucwords(strtolower($cita->respuesta_larga));
                            }

                        }

                        $inquiry->save();
                    }

                }
            }

        }
    } else {
        abort(404);
    }


});

Route::get('/importAll', function(){

    $estados = Estados::get();
    $states = States::get();
    if(!$states->count() >= 32) {
        foreach($estados as $estado){
            $state = new States();
            $state->code = $estado->clave;
            $state->name = $estado->nombre;
            $state->abbreviation = $estado->abrev;
            $state->save();
        }
    }

    $municipios = Municipios::get();
    $municipalies = Municipality::get();
    if(!$municipalies->count() >= 2492){
        foreach($municipios as $municipio){
            $muni = new Municipality;
            $muni->code = $municipio->clave;
            $muni->name = $municipio->nombre;
            $muni->state_id = $municipio->estado_id;
            $muni->save();
        }
    }

    $localidades = Localidades::get();
    $locations = Location::get();
    if(!$locations->count() >= 304375){
        foreach($localidades as $localidad){
            $local = new Location();
            $local->code = $localidad->clave;
            $local->name = $localidad->nombre;
            $local->latitude = $localidad->latitud;
            $local->longitude = $localidad->longitud;
            $local->lat = $localidad->lat;
            $local->ing = $localidad->ing;
            $local->altitude = $localidad->altitud;
            $local->municipality_id = $localidad->municipio_id;
            $local->save();
        }
    }

});

//logOut
Route::get('/logout',function(){
    Auth::logout();
    return redirect('/admin'); 
})->name('main.logout');