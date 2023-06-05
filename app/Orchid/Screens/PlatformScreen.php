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
        $user = User::select('id')->get();

        //Carbon
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $monthStartDate = $now->startOfMonth()->format('Y-m-d H:i');
        $monthEndDate = $now->endOfMonth()->format('Y-m-d H:i');
        $yearStartDate = $now->startOfYear()->format('Y-m-d H:i');
        $yearEndDate = $now->endOfYear()->format('Y-m-d H:i');
        $week = Inquiry::whereBetween('created_at', [$weekStartDate, $weekEndDate])->get();
        $month = Inquiry::whereBetween('created_at', [$monthStartDate, $monthEndDate])->get();
        $year = Inquiry::whereBetween('created_at', [$yearStartDate, $yearEndDate])->get();
        $last_patient = Inquiry::latest()->with('user')->first();

        return [
            'user' => Auth::user(),
            'metrics' => [
                'users' => $user->count(),
                'week' => $week->count(),
                'month' => $month->count(),
                'last_patient' => $last_patient->user->FullName,
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
                'Pacientes totales'    => 'metrics.users',
                'Citas esta semana'    => 'metrics.week',
                'Citas este mes'    => 'metrics.month',
            ]),
        ];
    }
}
