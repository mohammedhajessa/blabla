@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">My Journey Requests</h4>

    <!-- Debugging Tools (Remove in production) -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Debug Tools</h5>
            <button type="button" class="btn btn-sm btn-secondary" onclick="resetBookingCache()">Reset Booking Cache</button>
        </div>
        <div class="card-body">
            <div class="alert alert-info" role="alert">
                <strong>Pusher Status:</strong> <span id="pusher-status">Connecting...</span>
            </div>
            <div class="mb-2">
                <small class="text-muted">Your driver ID: {{ auth()->guard('driver')->id() }}</small><br>
                <small class="text-muted">Pusher Channel: driver-{{ auth()->guard('driver')->id() }}</small>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Notifications</h5>
            <small class="text-muted float-end">Real-time updates</small>
        </div>
        <div class="card-body">
            <div id="notifications-container">
                <div class="alert alert-info" id="no-notifications" role="alert">
                    No new notifications at this time
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Journey Requests Table -->
    <div class="card">
        <h5 class="card-header">My Booking Requests</h5>
        <div class="card-datatable table-responsive">
            <table class="datatables-journey-requests table">
                <thead class="border-top">
                    <tr>
                        <th>Journey ID</th>
                        <th>Passenger</th>
                        <th>Route</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="journey-requests-table-body">
                    @if($journeyRequests->count() > 0)
                        @foreach($journeyRequests as $request)
                        <tr id="journey-request-{{ $request->id }}">
                            <td><span class="badge bg-label-primary">{{ $request->journey_id }}</span></td>
                            <td>{{ $request->passenger->name }}
                                <br>
                                <small class="text-muted"><i class="bx bx-phone"></i><a href="https://chat.whatsapp.com/{{ $request->passenger->phone }}">{{ $request->passenger->phone }}</a></small>
                            </td>
                            <td>
                                <span class="badge bg-label-info">
                                    {{ $request->journey->pickupCity->name ?? 'Unknown' }} to {{ $request->journey->dropoffCity->name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-warning">
                                    {{ $request->journey->journey_date }} - {{ $request->journey->pickup_time }}
                                </span>
                            </td>
                            <td>
                                @if($request->status == 'pending')
                                    <span class="badge bg-label-warning">Pending</span>
                                @elseif($request->status == 'accepted')
                                    <span class="badge bg-label-success">Accepted</span>
                                @elseif($request->status == 'rejected')
                                    <span class="badge bg-label-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('driver.journeyRequests.updateStatus', $request->id) }}" method="POST" class="d-inline-block me-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this request?')">
                                            <i class="bx bx-check me-1"></i>Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('driver.journeyRequests.updateStatus', $request->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this request?')">
                                            <i class="bx bx-x me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr id="no-requests-row">
                            <td colspan="5" class="text-center">No journey requests found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
    // Enable Pusher logging for development
    Pusher.logToConsole = true;

    // Connect to Pusher
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        useTLS: true
    });

    // Debug function to reset booking cache
    function resetBookingCache() {
        localStorage.clear();
        console.log('Local storage cleared!');
        document.getElementById('pusher-status').textContent = 'Cache cleared. Reconnecting...';

        // Attempt to reconnect to Pusher
        pusher.disconnect();
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    // Subscribe to the driver's channel
    const driverId = '{{ auth()->guard('driver')->id() }}';
    const channelName = 'driver-' + driverId;
    var channel = pusher.subscribe(channelName);

    // Update connection status
    pusher.connection.bind('connected', function() {
        document.getElementById('pusher-status').textContent = 'Connected ✓';
        document.getElementById('pusher-status').className = 'text-success';
        console.log('Successfully connected to Pusher, listening on channel:', channelName);
    });

    pusher.connection.bind('error', function(err) {
        document.getElementById('pusher-status').textContent = 'Connection Error: ' + err.error.data.message;
        document.getElementById('pusher-status').className = 'text-danger';
        console.error('Pusher connection error:', err);
    });

    // Listen for new booking events
    channel.bind('new-booking', function(data) {
        console.log('New booking notification received!', data);

        // Hide the no notifications message
        document.getElementById('no-notifications').style.display = 'none';

        // Create a new notification
        var notification = `
            <div class="alert alert-primary alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <i class="bx bx-bell me-2"></i>
                    <div>
                        <strong>New Booking Request:</strong> ${data.message}
                        <br>
                        <small>Journey ID: ${data.journey_id} | Passenger ID: ${data.passenger_id}</small>
                        <br>
                        <div class="mt-2">
                            <form action="/dashboard/driver-journey-requests/update-status/${data.booking_id}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="accepted">
                                {{--  <button type="submit" class="btn btn-success btn-sm">Accept</button>  --}}
                            </form>
                            <form action="/dashboard/driver-journey-requests/update-status/${data.booking_id}" method="POST" style="display: inline; margin-left: 10px;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                {{--  <button type="submit" class="btn btn-danger btn-sm">Reject</button>  --}}
                            </form>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        // Add the notification to the container
        document.getElementById('notifications-container').innerHTML = notification + document.getElementById('notifications-container').innerHTML;

        // Play a notification sound if available
        try {
            var audio = new Audio('/assets/sounds/notification.mp3');
            audio.play().catch(e => console.warn('Could not play notification sound:', e));
        } catch (e) {
            console.warn('Error playing notification sound:', e);
        }
    });
</script>

@endsection
