<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\Residence;
// use Illuminate\Http\Request;

class AdminController extends Controller
{
// Function homes
    public function homes()
    {
        return view('adminauth.homes');
    }

// Function Resiences
public function residences()
{
    $residences = Residence::all(); // Ou une requête plus complexe
    
    return view('adminauth.residences', ['residences' => $residences]);
}

}
