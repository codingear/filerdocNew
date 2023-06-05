<?php

namespace App\Orchid\Layouts\Inquiry;

use App\Models\Inquiry;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

class InquiryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'inquiries';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Nombre')
                ->render(function (Inquiry $inquiry) {
                    return Link::make($inquiry->user->name.' '.@$inquiry->user->extra->last_name)
                        ->route('platform.inquiry.edit', $inquiry);
                }),

            TD::make('inquiry', 'Todas las consultas')
                ->render(function (Inquiry $inquiry) {
                    return Link::make($inquiry->user->getInquiryCount())
                        ->route('platform.inquiry.user', $inquiry->user);
                }),

            TD::make('created_at', __('Creado'))
                ->sort()
                ->render(fn (Inquiry $inquiry) => $inquiry->created_at->toDateTimeString()),
        ];
    }
}
