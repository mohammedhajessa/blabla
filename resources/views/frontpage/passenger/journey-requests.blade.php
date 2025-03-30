@extends('partial.main')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span></h4>
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span></h4>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Passenger /</span> My Journey Requests</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Journey Requests</h5>
                <div class="card-datatable table-responsive">
                    <table class="datatables-journey-requests table">
                        <thead class="border-top">
                            <tr>
                                <th>Journey Date</th>
                                <th>Route</th>
                                <th>Driver</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($journeyRequests as $request)
                                <tr>
                                    <td>
                                        <span class="badge bg-label-primary">
                                            {{ $request->journey->journey_date }}
                                            <br>
                                            {{ $request->journey->pickup_time }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">
                                            {{ $request->journey->pickupCity->name }} to {{ $request->journey->dropoffCity->name }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $request->journey->driver->name }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="ti ti-phone"></i>
                                            <a href="https://wa.me/{{ $request->journey->driver->phone }}">{{ $request->journey->driver->phone }}</a>
                                        </small>
                                    </td>
                                    <td>
                                        @if($request->journey->status == 'completed')
                                            @if($request->hasReview)
                                                <span class="badge bg-label-success">
                                                    <i class="ti ti-check me-1"></i> Review Submitted
                                                </span>
                                            @else
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $request->id }}">
                                                    <i class="ti ti-star"></i> Rate Journey
                                                </button>
                                            <!-- Review Modal -->
                                            <div class="modal fade" id="reviewModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Rate Your Journey</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('passenger.review') }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="passenger_id" value="{{ Auth::guard('passenger')->user()->id }}">
                                                                <input type="hidden" name="driver_id" value="{{ $request->journey->driver_id }}">
                                                                <input type="hidden" name="journey_id" value="{{ $request->journey_id }}">

                                                                <div class="mb-3">
                                                                    <label class="form-label">Rating</label>
                                                                    <div class="star-rating">
                                                                        <div class="d-flex gap-2">
                                                                            @for($i = 5; $i >= 1; $i--)
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}{{ $request->id }}" value="{{ $i }}">
                                                                                    <label class="form-check-label" for="rating{{ $i }}{{ $request->id }}">
                                                                                        <i class="ti ti-star"></i> {{ $i }}
                                                                                    </label>
                                                                                </div>
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="comment{{ $request->id }}" class="form-label">Your Comments</label>
                                                                    <textarea class="form-control" id="comment{{ $request->id }}" name="comment" rows="3" placeholder="Share your experience..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Submit Review</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @elseif($request->journey->status == 'pending')
                                            <span class="badge bg-label-warning">Journey in progress</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No journey requests found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('frontpage.index') }}" class="btn btn-primary waves-effect waves-light me-2">
            <i class="ti ti-car me-1"></i> Browse Available Journeys
        </a>
        <a href="{{ route('passenger.profile') }}" class="btn btn-outline-secondary waves-effect">
            <i class="ti ti-dashboard me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Star rating visual enhancement
        const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
        starInputs.forEach(input => {
            input.addEventListener('change', function() {
                const rating = this.value;
                const container = this.closest('.star-rating');
                const stars = container.querySelectorAll('label');

                stars.forEach((star, index) => {
                    if (index < 5 - rating) {
                        star.classList.remove('text-warning');
                    } else {
                        star.classList.add('text-warning');
                    }
                });
            });
        });
    });
</script>
@endpush

@endsection