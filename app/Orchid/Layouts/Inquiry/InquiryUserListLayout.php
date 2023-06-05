<?php

namespace App\Orchid\Layouts\Inquiry;

use App\Models\Inquiry;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Button;

class InquiryUserListLayout extends Table
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

            TD::make('diagnosis', __('Diagnóstico'))
                ->sort()
                ->render(fn (Inquiry $inquiry) => ($inquiry->diagnosis)? $inquiry->diagnosis : '-'),

            TD::make('medications', __('Medicamentos'))
                ->sort()
                ->render(fn (Inquiry $inquiry) => ($inquiry->medications)? $inquiry->medications : '-'),
            TD::make('weight', __('Peso'))
                ->sort()
                ->render(fn (Inquiry $inquiry) => $inquiry->weight.' Kg.'),

            TD::make('created_at', __('Fecha'))
                ->sort()
                ->render(fn (Inquiry $inquiry) => $inquiry->created_at->format('d-m-Y')),

            TD::make(__('Acciones'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Inquiry $inquiry) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Editar'))
                            ->route('platform.inquiry.edit', $inquiry->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Borrar'))
                            ->icon('bs.trash3')
                            ->confirm(__('Una vez que se elimine la cuenta, todos sus recursos y datos se eliminarán de forma permanente.'))
                            ->method('remove', [
                                'id' => $inquiry->id,
                            ]),
                    ])),
        ];
    }
}
