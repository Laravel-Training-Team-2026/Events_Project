@extends(backpack_view('blank'))

@section('content')
    @php
        $totalEvents = \App\Models\Event::count();
        $upcomingEvents = \App\Models\Event::whereDate('start_date', '>=', today())->count();
        $totalUsers = \App\Models\User::count();
        $totalBookings = \App\Models\Booking::count();
        $confirmedBookings = \App\Models\Booking::where('status', \App\Models\Booking::STATUS_CONFIRMED)->count();
        $cancelledBookings = \App\Models\Booking::where('status', \App\Models\Booking::STATUS_CANCELLED)->count();
    @endphp

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Events</p>
                    <h2 class="mb-0">{{ $totalEvents }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted mb-2">Upcoming Events</p>
                    <h2 class="mb-0">{{ $upcomingEvents }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Users</p>
                    <h2 class="mb-0">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Bookings</p>
                    <h2 class="mb-0">{{ $totalBookings }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted mb-2">Confirmed Bookings</p>
                    <h2 class="mb-0">{{ $confirmedBookings }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <p class="text-muted mb-2">Cancelled Bookings</p>
                    <h2 class="mb-0">{{ $cancelledBookings }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
