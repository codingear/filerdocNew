<?php

namespace App\Orchid\Layouts\User;

use App\Orchid\Filters\RoleFilter;
use App\Orchid\Filters\PatientFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            RoleFilter::class,
            PatientFilter::class
        ];
    }
}
