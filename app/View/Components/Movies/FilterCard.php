<?php

namespace App\View\Components\Movies;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterCard extends Component
{
    public array $listScreenings;

    public function __construct(
        public array $screenings =[],
       // public string $date,

    )
    {
        $this->listScreenings = (array_merge([null => 'Any screening'], $screenings));
        //$this->date = $date;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movies.filter-card');
    }
}
