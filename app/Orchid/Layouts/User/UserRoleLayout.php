<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Illuminate\Support\Facades\Auth;

class UserRoleLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        if(Auth::user()->inRole('doctor')){
            return [
                Select::make('user.roles')
                    ->options([
                        3  => 'Paciente',
                    ])
                    ->title(__('Name role'))
                    ->help('Specify which groups this account should belong to'),
            ];
        } else {
            return [
                Select::make('user.roles.')
                    ->fromModel(Role::class, 'name')
                    ->multiple()
                    ->title(__('Name role'))
                    ->help('Specify which groups this account should belong to'),
            ];
        }
        
    }
}
