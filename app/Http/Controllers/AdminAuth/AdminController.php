<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\Residence;
use App\Models\Booking;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{ // Tableau de bord
    public function homes()
    {
        // Compter le nombre total de réservations
        $totalBookings = Booking::count();

        // Compter les réservations annulées
        $cancelledBookings = Booking::where('statut', 'Annulé')->count();

        // Compter les réservations confirmées
        $confirmedBookings = Booking::where('statut', 'Confirmé')->count();

        // Compter les réservations terminées
        $completedBookings = Booking::where('statut', 'Terminé')->count();

        // Compter les réservations en cours
        $pendingBookings = Booking::where('statut', 'Attente')->count();

        // Vous pouvez ajuster les noms de statut ('cancelled', 'confirmed', etc.) pour qu'ils correspondent à ceux que vous utilisez dans votre base de données.

        // Retourner la vue avec toutes les données
        return view('adminauth.dashboard', [
            'totalBookings' => $totalBookings,
            'cancelledBookings' => $cancelledBookings,
            'confirmedBookings' => $confirmedBookings,
            'completedBookings' => $completedBookings,
            'pendingBookings' => $pendingBookings,
        ]);
    }

    /**
     * Résidences
     */
    public function residences()
    {
        $residences = Residence::all();
        return view('adminauth.residences.index', compact('residences'));
    }

    public function createResidence()
    {
        return view('adminauth.residences.create');
    }

    public function storeResidence(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'prix' => 'required|numeric',
        ]);

        Residence::create($validated);
        return redirect()->route('adminauth.residences.index')->with('success', 'Résidence ajoutée');
    }

    public function editResidence(Residence $residence)
    {
        return view('adminauth.residences.edit', compact('residence'));
    }

    public function updateResidence(Request $request, Residence $residence)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'prix' => 'required|numeric',
        ]);

        $residence->update($validated);
        return redirect()->route('adminauth.residences.index')->with('success', 'Résidence mise à jour');
    }

    public function destroyResidence(Residence $residence)
    {
        $residence->delete();
        return redirect()->route('adminauth.residences.index')->with('success', 'Résidence supprimée');
    }

    /**
     * Réservations
     */
    public function index()
    {
        $bookings = Booking::paginate(8);

        return view('adminauth.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('adminauth.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        return view('adminauth.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $booking->update($validated);
        return redirect()->route('adminauth.bookings.index')->with('success', 'Réservation mise à jour');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('adminauth.bookings.index')->with('success', 'Réservation supprimée');
    }

    public function approveBooking(Booking $booking)
    {
        $booking->update(['statut' => 'approved']);
        return redirect()->route('adminauth.bookings.index')->with('success', 'Réservation approuvée');
    }

    public function rejectBooking(Booking $booking)
    {
        $booking->update(['statut' => 'rejected']);
        return redirect()->route('adminauth.bookings.index')->with('success', 'Réservation rejetée');
    }

    /**
     * Clients
     */
    public function clients()
    {
        $clients = User::paginate(10);
        return view('adminauth.clients.index', compact('clients'));
    }

    public function getClientBookings($id)
    {
        $client = User::with('bookings.residence')->findOrFail($id);

        // Retourner une vue partielle (Blade) ou du JSON
        return response()->json([
            'client' => $client->only(['id', 'name', 'email']),
            'bookings' => $client->bookings->map(function ($b) {
                return [
                    'id' => $b->id,
                    'residence' => $b->residence->nom ?? 'N/A',
                    'date_debut' => $b->date_debut,
                    'date_fin' => $b->date_fin,
                    'statut' => $b->statut,
                ];
            })
        ]);
    }

    public function showClient(User $client)
    {
        return view('adminauth.clients.show', compact('client'));
    }

    public function destroyClient(User $client)
    {
        $client->delete();
        return redirect()->route('adminauth.clients.index')->with('success', 'Client supprimé');
    }

    /**
     * Paiements
     */
    public function payments()
    {
        // Exemple : si tu as un modèle Payment
        $payments = \App\Models\Payment::paginate(10);
        // $payments = \App\Models\Payment::all();
        return view('adminauth.payments.index', compact('payments'));
    }

    public function showPayment(\App\Models\Payment $payment)
    {
        return view('adminauth.payments.show', compact('payment'));
    }

    /**
     * Profil admin
     */
    public function profile()
    {
        $admin = auth('admin')->user();
        return view('adminauth.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = auth('admin')->admin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);
        $admin->update($validated);

        return redirect()->route('adminauth.profile')->with('success', 'Profil mis à jour');
    }

    // Utilisateur
    public function users()
    {
        $users = Admin::paginate(10);
        return view('adminauth.users.index', compact('users'));
    }
    public function createUser()
    {
        return view('admin.users.create');
    }
    public function storeUser(Request $request)
    { /* create */
    }
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    public function updateUser(Request $request, User $user)
    { /* update */
    }
    public function destroyUser(User $user)
    {
        $user->delete();
        return back();
    }
}
