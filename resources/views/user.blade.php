@foreach($pacientes as $paciente)
    <table style="background-color:#ececec;margin-bottom:20px;">
        <thead>
            <tr>
                <td>ID</td>
                <td>Paciente</td>
                <td>Fecha</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$paciente->id}}</td>
                <td>{{$paciente->nombre}} {{$paciente->apellido_paterno}} {{$paciente->apellido_materno}}</td>
            </tr>
            @if($paciente->citas->count() > 0)
                @foreach($paciente->citas as $cita)
                    <tr>
                        <td>{{$cita->res_direct->pregunta}}</td>
                        <td>@if(!empty($cita->respuesta))
                                {{$cita->respuesta}}
                            @else
                                {{$cita->respuesta_larga}}
                            @endif
                        </td>
                        <td>{{$cita->creado}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endforeach

