@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Journeys</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Journeys List</h5>
                    <a href="{{ route('journeys.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Add New Journey
                    </a>
                </div>

                <!-- Filter Section -->
                <div class="card-body">
                    <form id="journeyFilterForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_pickup_city" class="form-label">Pickup City</label>
                            <select id="filter_pickup_city" name="pickup_city_id" class="form-select">
                                <option value="">All Cities</option>
                                @foreach($cities ?? [] as $city)
                                    <option value="{{ $city->id }}" {{ request('pickup_city_id') == $city->id ? 'selected' : '' }}>
                                        {{ strtoupper(ucfirst($city->name)) }}
                                        @if($city->region)
                                            ({{ strtoupper(ucfirst($city->region->name)) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_dropoff_city" class="form-label">Dropoff City</label>
                            <select id="filter_dropoff_city" name="dropoff_city_id" class="form-select">
                                <option value="">All Cities</option>
                                @foreach($cities ?? [] as $city)
                                    <option value="{{ $city->id }}" {{ request('dropoff_city_id') == $city->id ? 'selected' : '' }}>
                                        {{ strtoupper(ucfirst($city->name)) }}
                                        @if($city->region)
                                            ({{ strtoupper(ucfirst($city->region->name)) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_date" class="form-label">Journey Date</label>
                            <input type="date" class="form-control" id="filter_date" name="journey_date"
                                value="{{ request('journey_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="filter_status" class="form-label">Status</label>
                            <select id="filter_status" name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="button" id="filterButton" class="btn btn-primary">
                                    <i class="ti ti-filter me-1"></i> Filter
                                </button>
                                <button type="button" id="resetButton" class="btn btn-outline-secondary">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End Filter Section -->

                <div class="card-datatable table-responsive">
                    <table class="datatables-journeys table">
                        <thead class="border-top">
                            <tr>
                                <th>ID</th>
                                <th>Driver Name</th>
                                <th>Pickup City</th>
                                <th>Dropoff City</th>
                                <th>Pickup Time</th>
                                <th>Arrival Time</th>
                                <th>Distance</th>
                                <th>Available Seats</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Reviews</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="journeysTableBody">
                            @forelse($journeys as $journey)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-label-primary">{{ ucfirst($journey->driver->name) }}</span></td>
                                    <td><span class="badge bg-label-primary">{{ $journey->pickupCity->name }}</span></td>
                                    <td><span class="badge bg-label-primary">{{ $journey->dropoffCity->name }}</span></td>
                                    <td><span class="badge bg-label-warning">{{ $journey->pickup_time ? $journey->pickup_time : 'N/A' }}</span></td>
                                    <td><span class="badge bg-label-success">{{ $journey->arrival_time ? $journey->arrival_time : 'N/A' }}</span></td>
                                    <td><span class="badge bg-label-primary">{{ $journey->distance }} km</span></td>
                                    <td><span class="badge bg-label-danger">{{ $journey->available_seats }} seats</span></td>
                                    <td><span class="badge bg-label-primary">{{ $journey->price }} tr</span></td>
                                    <td>
                                        <form action="{{ route('journeys.updateStatus', $journey->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm registration-status-select"
                                                data-driver-id="{{ $journey->id }}"
                                                data-url="{{ route('journeys.updateStatus', $journey->id) }}"
                                                onchange="this.form.submit()"
                                                style="min-width: 130px;
                                                    background-color: {{ $journey->status == 'pending' ? '#FFF4E5' : ($journey->status == 'confirmed' ? '#E7F6E7' : ($journey->status == 'completed' ? '#E7F6E7' : '#FFE7E5')) }};
                                                    border-width: 2px;
                                                    border-style: solid;
                                                    border-color: {{ $journey->status == 'pending' ? '#FFAB00' : ($journey->status == 'confirmed' ? '#71DD37' : ($journey->status == 'completed' ? '#71DD37' : '#FF3E1D')) }};
                                                    color: {{ $journey->status == 'pending' ? '#B76E00' : ($journey->status == 'confirmed' ? '#2B7424' : ($journey->status == 'completed' ? '#2B7424' : '#B71D1D')) }};
                                                    font-weight: 500;
                                                    border-radius: 6px;
                                                    padding: 0.3rem 0.5rem;">
                                                <option value="pending"
                                                    {{ $journey->status == 'pending' ? 'selected' : '' }}
                                                    style="background-color: #FFF4E5; color: #B76E00">
                                                    Pending
                                                </option>
                                                <option value="completed"
                                                    {{ $journey->status == 'completed' ? 'selected' : '' }}
                                                    style="background-color: #E7F6E7; color: #2B7424">
                                                    Completed
                                                </option>
                                                <option value="cancelled"
                                                    {{ $journey->status == 'cancelled' ? 'selected' : '' }}
                                                    style="background-color: #FFE7E5; color: #B71D1D">
                                                    Cancelled
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('driver.reviews.show', $journey->id) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('journeys.edit', $journey->id) }}"
                                                class="btn btn-icon btn-sm btn-text-secondary rounded-pill">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('journeys.destroy', $journey->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-icon btn-sm btn-text-secondary rounded-pill show-confirm"
                                                    onclick="return confirm('Are you sure you want to delete this journey?')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No journeys found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter button click handler
        document.getElementById('filterButton').addEventListener('click', function() {
            filterJourneys();
        });

        // Reset button click handler
        document.getElementById('resetButton').addEventListener('click', function() {
            document.getElementById('filter_pickup_city').value = '';
            document.getElementById('filter_dropoff_city').value = '';
            document.getElementById('filter_date').value = '';
            document.getElementById('filter_status').value = '';
            // Reload the page instead of using AJAX
            window.location.href = "{{ route('journeys.index') }}";
        });

        function filterJourneys() {
            const pickup_city = document.getElementById('filter_pickup_city').value;
            const dropoff_city = document.getElementById('filter_dropoff_city').value;
            const journey_date = document.getElementById('filter_date').value;
            const status = document.getElementById('filter_status').value;

            // Build the URL with query parameters
            let url = "{{ route('journeys.index') }}?";
            if (pickup_city) url += `pickup_city_id=${pickup_city}&`;
            if (dropoff_city) url += `dropoff_city_id=${dropoff_city}&`;
            if (journey_date) url += `journey_date=${journey_date}&`;
            if (status) url += `status=${status}&`;

            // Navigate to the filtered URL
            window.location.href = url;
        }

        // Client-side filtering for immediate feedback
        const filterTableRows = function() {
            const pickup_city_select = document.getElementById('filter_pickup_city');
            const dropoff_city_select = document.getElementById('filter_dropoff_city');
            const journey_date = document.getElementById('filter_date').value;
            const status = document.getElementById('filter_status').value.toLowerCase();

            // Get the selected city text instead of value for display filtering
            const pickup_city_text = pickup_city_select.options[pickup_city_select.selectedIndex].text.toLowerCase();
            const dropoff_city_text = dropoff_city_select.options[dropoff_city_select.selectedIndex].text.toLowerCase();

            const pickup_city_value = pickup_city_select.value;
            const dropoff_city_value = dropoff_city_select.value;

            const rows = document.querySelectorAll('#journeysTableBody tr');

            rows.forEach(row => {
                let showRow = true;

                // Check pickup city (column index 2)
                if (pickup_city_value && row.cells[2]) {
                    const cityText = row.cells[2].textContent.toLowerCase();
                    if (!cityText.includes(pickup_city_text.split('(')[0].trim())) {
                        showRow = false;
                    }
                }

                // Check dropoff city (column index 3)
                if (dropoff_city_value && showRow && row.cells[3]) {
                    const cityText = row.cells[3].textContent.toLowerCase();
                    if (!cityText.includes(dropoff_city_text.split('(')[0].trim())) {
                        showRow = false;
                    }
                }

                // Check status (column index 9)
                if (status && showRow && row.cells[9]) {
                    const statusCell = row.cells[9];
                    const statusSelect = statusCell.querySelector('select');
                    if (statusSelect) {
                        const selectedOption = statusSelect.options[statusSelect.selectedIndex];
                        if (!selectedOption.value.toLowerCase().includes(status)) {
                            showRow = false;
                        }
                    }
                }

                // Date filtering would require parsing the date format in the table
                // This is a simplified version that just checks if the date string contains the filter value
                if (journey_date && showRow && row.cells[4]) {
                    const pickupTimeCell = row.cells[4].textContent;
                    if (!pickupTimeCell.includes(journey_date)) {
                        showRow = false;
                    }
                }

                row.style.display = showRow ? '' : 'none';
            });

            // Show "No journeys found" if all rows are hidden
            const visibleRows = document.querySelectorAll('#journeysTableBody tr:not([style*="display: none"])');
            const noResultsRow = document.querySelector('#journeysTableBody tr.no-results');

            if (visibleRows.length === 0) {
                if (!noResultsRow) {
                    const tbody = document.getElementById('journeysTableBody');
                    const newRow = document.createElement('tr');
                    newRow.className = 'no-results';
                    newRow.innerHTML = '<td colspan="12" class="text-center">No journeys found for the selected filters</td>';
                    tbody.appendChild(newRow);
                } else {
                    noResultsRow.style.display = '';
                }
            } else if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
        };

        // Add event listeners for real-time filtering
        document.getElementById('filter_pickup_city').addEventListener('change', filterTableRows);
        document.getElementById('filter_dropoff_city').addEventListener('change', filterTableRows);
        document.getElementById('filter_date').addEventListener('change', filterTableRows);
        document.getElementById('filter_status').addEventListener('change', filterTableRows);
    });
</script>
@endsection
