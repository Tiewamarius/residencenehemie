<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\Residence;
use App\Models\Booking;
use Illuminate\Http\Request;

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

// fonction de booking views
public function index()
    {
        // Récupère toutes les réservations depuis la base de données
        $bookings = Booking::all();

        // Passe les réservations à la vue
        return view('adminauth.bookings', compact('bookings'));
    }
public function show(Booking $booking)
    {
        return view('adminauth.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // $apartments = Apartment::all();
        return view('adminauth.edit', compact('booking'));
    }


    public function update(Request $request, Booking $booking)
{
    // Validez la requête
    $validatedData = $request->validate([
        'client_name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        // Ajoutez les autres règles de validation ici
    ]);

    // Mettez à jour la réservation
    $booking->update($validatedData);

    // Redirigez l'utilisateur
    return redirect()->back()->with('success', 'Réservation mise à jour avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->back()->with('success', 'Réservation supprimée avec succès.');
    }

}
