<?php

namespace App\View\Components\Movies;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterCard extends Component
{
    public array $listGenres;

    public function __construct(
        public array $genres,
        public ?string $genre = null,
        public ?string $title = null,
        public ?string $synopsis = null,
        public ?string $allMoviesBool = null,
        public string $filterAction,
        public string $resetUrl,

    )
    {

        $filteredGenres = array_filter($genres, function($code) {
            return $code !== 'DEFAULT';
        }, ARRAY_FILTER_USE_KEY);

        $this->listGenres = array_merge([null => 'Any genre'], $filteredGenres);

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movies.filter-card');

    }
}
