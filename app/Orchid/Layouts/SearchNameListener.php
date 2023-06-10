<?php

namespace App\Orchid\Layouts;

use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;

class SearchNameListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'name',
        'last_name',
        'mother_last_name',
    ];

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::rows([
                Input::make('name')
                    ->title('Nombre')
                    ->type('text'),

                Input::make('last_name')
                    ->title('Apellido paterno')
                    ->type('text'),

                Input::make('mother_last_name')
                    ->title('Apellido materno')
                    ->type('text'),

                Input::make('result')
                    ->readonly()
                    ->canSee($this->query->has('result')),
            ]),
        ];
    }

    /**
     * Update state
     *
     * @param \Orchid\Screen\Repository $repository
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Orchid\Screen\Repository
     */
    public function handle(Repository $repository, Request $request): Repository
    {-
        [$name, $last_name, $mother_last_name] = $request->all();
        return $repository
            ->set('name', $name)
            ->set('last_name', $last_name)
            ->set('mother_last_name', $mother_last_name)
            ->set('result', $name + $last_name + $mother_last_name);
    }
}
