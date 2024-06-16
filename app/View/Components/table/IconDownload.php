<?php

namespace App\View\Components\table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IconDownload extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $href = "#"
    ){
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.icon-download');
    }
}
