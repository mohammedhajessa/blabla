@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">My Journey Requests</h4>

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

{{--  <script>
    // Enable Pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    var channel = pusher.subscribe('driver-{{ auth()->guard('driver')->id() }}');
    channel.bind('new-booking', function(data) {
        // Hide the no notifications message
        $('#no-notifications').hide();

        // Create a new notification
        var notification = `
            <div class="alert alert-primary alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <i class="bx bx-bell me-2"></i>
                    <div>
                        <strong>New Booking Request:</strong> ${data.message}
                        <br>
                        <small>Journey ID: ${data.journey_id}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        // Add the notification to the container
        $('#notifications-container').prepend(notification);

        // Play a notification sound
        var audio = new Audio('/assets/sounds/notification.mp3');
        audio.play();

        // Refresh the journey requests table
        location.reload();
    });
</script>  --}}

@endsection