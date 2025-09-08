@extends('adminauth.AdminDashboard')
@section('content')
<section class="h-100 h-custom" style="background-color: #d6d1d22c;text-align:center;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-8 col-xl-6">
                <div class="card rounded-3">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/img3.webp"
                        class="w-100" style="border-top-left-radius: .3rem; border-top-right-radius: .3rem;"
                        alt="Sample photo">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">Modifier la réservation</h4>

                        <form class="px-md-2" action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div data-mdb-input-init class="form-outline mb-4">
                                <center><label class="form-label" for="form3Example1q">N° Reservation</label>
                                    <input type="text" id="form3Example1q" value="{{ old('numero_reservation', $booking->numero_reservation) }}" class="form-control" style="text-align: center;" readonly />

                                </center>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">

                                    <div data-mdb-input-init class="form-outline datepicker">
                                        <input type="date" name="date_arrivee" id="start_date"
                                            value="{{ old('start_date', $booking->start_date) }}" class="form-control" id="exampleDatepicker1" />
                                        <label for="exampleDatepicker1" class="form-label">Nouvelle Date</label>
                                    </div>

                                </div>
                                <div class="col-md-6 mb-4">

                                    <div data-mdb-input-init class="form-outline datepicker">
                                        <input type="date" name="date_depart" id="start_date"
                                            value="{{ old('start_date', $booking->start_date) }}" class="form-control" id="exampleDatepicker1" />
                                        <label for="exampleDatepicker1" class="form-label">Nouvelle Date</label>
                                    </div>

                                </div>

                            </div>
                            <a href="{{ url()->previous() }}" data-mdb-button-init data-mdb-ripple-init class="btn btn-lg mb-1" style="background-color: red;">
                                Annuler
                            </a>

                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-lg mb-1" style="background-color: #08e037ff" ;>Confirmé</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection