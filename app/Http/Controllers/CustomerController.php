<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AdministrativeFormRequest;
use Illuminate\Support\Facades\Storage;
class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $administrativesQuery = User::where('type','==', 'C')
            ->orderBy('name');
        $filterByName = $request->query('name');
        $user = $request->user();
        if ($filterByName) {
            $administrativesQuery->where('name', 'like', "%$filterByName%");
        }
        $administratives = $administrativesQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'customers.index',
            compact('customers', 'filterByName', 'user')
        );
    }
}
