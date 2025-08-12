@extends('adminauth.AdminDashboard')

@section('content')
    <div class="container">
        <h2>Détails de la Réservation #{{ $booking->id }}</h2>

        <div class="card">
            <div class="card-header">
                Informations sur la Réservation
            </div>
            <div class="card-body">
                <p><strong>Nom du Client :</strong> {{ $booking->client_name }}</p>
                <p><strong>Date de Début :</strong> {{ $booking->start_date }}</p>
                <p><strong>Date de Fin :</strong> {{ $booking->end_date }}</p>
                </div>
        </div>

        <a href="" class="btn btn-secondary mt-3">Retour à la liste</a>
    </div>
@endsection