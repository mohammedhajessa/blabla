@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Driver /</span> Reviews
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Reviews</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($reviews->isEmpty())
                        <div class="alert alert-info">
                            You don't have any reviews yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Passenger</th>
                                        <th>Journey</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>{{ $review->passenger->name }}</td>
                                            <td>
                                                {{ $review->journey->pickupCity->name }} to {{ $review->journey->dropoffCity->name }}
                                                <br>
                                                <small class="text-muted">{{ $review->journey->journey_date }}</small>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="ti ti-star-filled text-warning"></i>
                                                        @else
                                                            <i class="ti ti-star text-muted"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>{{ $review->comment }}</td>
                                            <td>{{ $review->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Average Rating Card -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Average Rating</h5>
                    <div class="d-flex align-items-center">
                        <div class="display-4 me-3">
                            {{ number_format($reviews->avg('rating'), 1) }}
                        </div>
                        <div>
                            <div class="rating mb-2">
                                @php $avgRating = $reviews->avg('rating'); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($avgRating))
                                        <i class="ti ti-star-filled text-warning"></i>
                                    @else
                                        <i class="ti ti-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-muted">
                                Based on {{ $reviews->count() }} reviews
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection