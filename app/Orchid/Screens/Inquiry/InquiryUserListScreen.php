<?php

namespace App\Orchid\Screens\Inquiry;

use App\Models\Inquiry;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use App\Orchid\Layouts\Inquiry\InquiryUserListLayout;

class InquiryUserListScreen extends Screen
{
    public $user;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */

    public function query(User $user): iterable
    {
        $this->user = $user;
        return [
            'inquiries' => $user->inquiries
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Consultas';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Aqúi podrás gestionar las consultas";
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
                ->route('platform.systems.users.edit',$this->user->id),

            Link::make('Crear cita')
                ->icon('pencil')
                ->route('platform.inquiry.edit')
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
            InquiryUserListLayout::class
        ];
    }
}
