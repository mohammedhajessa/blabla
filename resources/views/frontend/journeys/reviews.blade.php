@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Journeys /</span> Reviews</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Passenger Reviews</h5>
                <div class="card-body">
                    @if($reviews->count() > 0)
                        <div class="row">
                            @foreach($reviews as $review)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar me-3">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ substr($review->passenger->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $review->passenger->name }}</h6>
                                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <span class="badge bg-label-warning">{{ $review->rating }}/5</span>
                                                    </div>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="ti ti-star-filled text-warning"></i>
                                                            @else
                                                                <i class="ti ti-star text-muted"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="card-text">{{ $review->comment }}</p>

                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="ti ti-map-pin me-1"></i>
                                                    {{ $review->journey->pickupCity->name }} to {{ $review->journey->dropoffCity->name }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ti ti-message-circle-off ti-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No reviews found for this journey</h6>
                            <p>Once passengers leave reviews, they will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('journeys.index') }}" class="btn btn-primary">
            <i class="ti ti-arrow-left me-1"></i> Back to Journeys
        </a>
    </div>
</div>
@endsection