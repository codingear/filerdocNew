<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('user.last_name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Apellido paterno')),
            
            Input::make('user.mother_last_name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Apellido materno')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),

            Input::make('user.phone')
                ->type('tel')
                ->max(255)
                ->mask('(999) 999-9999')
                ->required()
                ->title(__('Teléfono')),
        ];
    }
}
