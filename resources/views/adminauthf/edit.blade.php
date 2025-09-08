@extends('adminauth.AdminDashboard')

@section('content')
    <div class="container">
        <h2>Modifier la Réservation #{{ $booking->id }}</h2>

        <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="client_name">Nom du Client</label>
                <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name', $booking->client_name) }}">
            </div>

            <div class="form-group">
                <label for="start_date">Date de Début</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $booking->start_date) }}">
            </div>

            <div class="form-group">
                <label for="end_date">Date de Fin</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $booking->end_date) }}">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection