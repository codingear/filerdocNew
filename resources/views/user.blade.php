<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Paciente</td>
        </tr>
    </thead>
    <tbody>
        @if($paciente->citas->count() > 0)
            <tr>
                <td>{{$paciente->id}}</td>
                <td>{{$paciente->nombre}}</td>
            </tr>
            <tr>
                <td>{{$paciente->id_paciente}}</td>
            </tr>
            @foreach($paciente->citas as $cita)
                <tr>
                    <td>{{$cita->res_direct->pregunta}}</td>
                    <td>@if(!empty($cita->respuesta))
                            {{$cita->respuesta}}
                        @else
                            {{$cita->respuesta_larga}}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

