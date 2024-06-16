<?php

namespace App\View\Components\tickets;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ticketsTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
    public $tickets    
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tickets.tickets-table');
    }
}
