<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (User $user) => new Persona($user->presenter())),

            /*TD::make('patient_id', __('ID del paciente'))
                ->sort()
                ->filter(Input::make())
                ->render(fn (User $user) => @$user->datasheet->patient_id),*/

            TD::make('email', __('Email'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (User $user) => ModalToggle::make($user->email)
                    ->modal('asyncEditUserModal')
                    ->modalTitle($user->presenter()->title())
                    ->method('saveUser')
                    ->asyncParameters([
                        'user' => $user->id,
                    ])),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(fn (User $user) => $user->updated_at->toDateTimeString()),

            TD::make(__('Acciones'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (User $user) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([

                        Link::make(__('Todas las citas'))
                            ->route('platform.inquiry.user', $user->id)
                            ->icon('bs.inboxes'),

                        Link::make(__('Historial clínico'))
                            ->route('platform.historical.edit', $user->id)
                            ->icon('bs.list'),

                        Link::make(__('Ficha técnica'))
                            ->route('platform.datasheet.edit', $user->id)
                            ->icon('bs.person'),

                        Link::make(__('Editar usuario'))
                            ->route('platform.systems.users.edit', $user->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Borrar'))
                            ->icon('bs.trash3')
                            ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                            ->method('remove', [
                                'id' => $user->id,
                            ]),
                    ])),
        ];
    }
}
