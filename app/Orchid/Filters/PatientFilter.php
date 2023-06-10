<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
class PatientFilter extends Filter
{    
    public function name(): string
    {
        return __('Buscar');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['name','last_name','mother_last_name'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $builder->where('name', 'LIKE', '%' . $this->request->get('name') . '%')
            ->where('last_name', 'LIKE','%'.$this->request->get('last_name').'%')
            ->where('mother_last_name','LIKE','%'.$this->request->get('mother_last_name').'%')
            ->get();
        return $builder;
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('name')
                ->type('text')
                ->value($this->request->get('name'))
                ->title('Nombre'),

            Input::make('last_name')
                ->type('text')
                ->value($this->request->get('last_name'))
                ->title('Apellido paterno'),

            Input::make('mother_last_name')
                ->type('text')
                ->value($this->request->get('mother_last_name'))
                ->title('Apellido materno'),
        ];
    }
}
