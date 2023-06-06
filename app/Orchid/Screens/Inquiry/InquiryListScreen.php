<?php

namespace App\Orchid\Screens\Inquiry;

use App\Models\User;
use App\Models\Inquiry;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use App\Orchid\Layouts\Inquiry\InquiryListLayout;

class InquiryListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'inquiries' => Inquiry::where('doctor_id',Auth::user()->id)->orderBy('created_at','desc')->paginate()
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
        return "Todas las consultas";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nueva consulta')
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
            InquiryListLayout::class
        ];
    }
}
