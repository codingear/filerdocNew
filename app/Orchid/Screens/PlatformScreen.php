<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Inquiry;
use Carbon\Carbon;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public $user;    
    public function query(): iterable
    {
        $patients = Auth::user()->patients;

        //Carbon
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $monthStartDate = $now->startOfMonth()->format('Y-m-d H:i');
        $monthEndDate = $now->endOfMonth()->format('Y-m-d H:i');
        $yearStartDate = $now->startOfYear()->format('Y-m-d H:i');
        $yearEndDate = $now->endOfYear()->format('Y-m-d H:i');
        $week = Inquiry::where('user_id',Auth::user()->id)->whereBetween('created_at', [$weekStartDate, $weekEndDate])->get();
        $month = Inquiry::where('user_id',Auth::user()->id)->whereBetween('created_at', [$monthStartDate, $monthEndDate])->get();
        $year = Inquiry::where('user_id',Auth::user()->id)->whereBetween('created_at', [$yearStartDate, $yearEndDate])->get();
        $last_patient = Inquiry::where('doctor_id',Auth::user()->id)->latest()->with('user')->first();

        return [
            'user' => Auth::user(),
            'metrics' => [
                'patients' => @$patients->count(),
                'week' => @$week->count(),
                'month' => @$month->count(),
                'last_patient' => @$last_patient->user->FullName,
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Bienvenido '.$this->user->FullName;
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Gracias por preferir FilerDoc';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Último paciente'    => 'metrics.last_patient',
                'Pacientes totales'    => 'metrics.patients',
                'Consultas esta semana'    => 'metrics.week',
                'Consultas este mes'    => 'metrics.month',
            ]),
        ];
    }
}
