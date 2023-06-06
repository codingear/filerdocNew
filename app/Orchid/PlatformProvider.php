<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make(__('Pacientes'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Pacientes y consultas')),

            Menu::make(__('Roles'))
                ->icon('bs.lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make(__('Cerrar sesión'))
                ->icon('lock')
                ->title('Administración')
                ->route('main.logout')

        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            ItemPermission::group(__('Inquiry'))
                ->addPermission('platform.inquiry.edit', __('Edit'))
                ->addPermission('platform.inquiry.list', __('View all'))
                ->addPermission('platform.inquiry.user',__('All inquiries for user')),

            ItemPermission::group(__('Historical'))
                ->addPermission('platform.historical.edit', __('Edit')),

            ItemPermission::group(__('Datasheet'))
                ->addPermission('platform.datasheet.edit', __('Edit'))

        ];
    }
}
