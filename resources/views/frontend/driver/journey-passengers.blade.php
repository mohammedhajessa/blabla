@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <i class="ti ti-user-check me-2"></i>Journey Passengers Management
    </h4>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="ti ti-users"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $journeyPassengers->count() }}</h5>
                            <small>Total Passengers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="ti ti-car"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $journeyPassengers->pluck('journey_id')->unique()->count() }}</h5>
                            <small>Active Journeys</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="ti ti-calendar-check"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $journeyPassengers->where('created_at', '>=', now()->subDays(7))->count() }}</h5>
                            <small>New This Week</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" id="passenger-search" placeholder="Search passenger name...">
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        <select class="form-select" id="journey-filter">
                            <option value="">All Journeys</option>
                            @foreach($journeyPassengers->pluck('journey_id')->unique() as $journeyId)
                                <option value="{{ $journeyId }}">Journey #{{ $journeyId }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Journey Passengers Table -->
    <div class="card">
        <h5 class="card-header d-flex justify-content-between align-items-center">
            <span><i class="ti ti-list-check me-2"></i>Accepted Passengers</span>
            <button class="btn btn-sm btn-primary" id="export-btn">
                <i class="ti ti-export me-1"></i>Export
            </button>
        </h5>
        <div class="card-datatable table-responsive">
            <table class="datatables-journey-passengers table border-top" id="passengers-table">
                <thead>
                    <tr>
                        <th>Journey ID</th>
                        <th>Passenger Name</th>
                        <th>Route</th>
                        <th>Date & Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($journeyPassengers->count() > 0)
                        @foreach($journeyPassengers as $passenger)
                        <tr class="passenger-row" data-journey-id="{{ $passenger->journey_id }}" data-passenger-name="{{ $passenger->passenger->name }}">
                            <td><span class="badge bg-label-primary">{{ $passenger->journey_id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded-circle bg-label-info">
                                            {{ substr($passenger->passenger->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>{{ $passenger->passenger->name }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">
                                    {{ $passenger->journey->pickupCity->name ?? 'Unknown' }} to {{ $passenger->journey->dropoffCity->name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-muted">
                                    <i class="ti ti-calendar me-1"></i>{{ $passenger->journey->journey_date }}
                                    <br>
                                    <i class="ti ti-time me-1"></i>{{ $passenger->journey->pickup_time }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="https://wa.me/{{ $passenger->passenger->phone }}" class="btn btn-sm btn-icon btn-success me-2"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="Whatsapp">
                                        <i class="ti ti-brand-whatsapp"></i>
                                    </a>
                                    <a href="{{ route('driver.journeyPassenger', $passenger->passenger->id) }}" class="btn btn-sm btn-icon btn-primary me-2"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                        <i class="ti ti-file-description"></i>
                                    </a>
                                    @if($passenger->journey->status == 'completed')
                                        <a href="{{ route('driver.reviews.show', $passenger->journey->id) }}" class="btn btn-sm btn-icon btn-warning me-2"
                                           data-bs-toggle="tooltip" data-bs-placement="top" title="View Reviews">
                                            <i class="ti ti-star"></i>
                                        </a>
                                    @endif

                                    @if($passenger->journey->status != 'completed')
                                        <form action="{{ route('driver.journeyPassenger.destroy', $passenger->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-danger"
                                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Passenger"
                                                   onclick="return confirm('Are you sure you want to remove this passenger?')">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ti ti-user-x mb-2" style="font-size: 2rem;"></i>
                                    <p>No passengers found for your journeys</p>
                                    <a href="{{ route('journeys.index') }}" class="btn btn-sm btn-primary">
                                        <i class="ti ti-car me-1"></i>View My Journeys
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Passenger List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Export Format</label>
                    <select class="form-select" id="export-format">
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Include Fields</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include-journey-id" checked>
                        <label class="form-check-label" for="include-journey-id">Journey ID</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include-passenger-name" checked>
                        <label class="form-check-label" for="include-passenger-name">Passenger Name</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include-route" checked>
                        <label class="form-check-label" for="include-route">Route</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include-date-time" checked>
                        <label class="form-check-label" for="include-date-time">Date & Time</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-export">Export</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passengerSearch = document.getElementById('passenger-search');
    const journeyFilter = document.getElementById('journey-filter');
    const passengerRows = document.querySelectorAll('.passenger-row');
    const exportBtn = document.getElementById('export-btn');
    const confirmExportBtn = document.getElementById('confirm-export');
    const exportModal = new bootstrap.Modal(document.getElementById('exportModal'));

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search by passenger name
    passengerSearch.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();

        passengerRows.forEach(row => {
            const passengerName = row.getAttribute('data-passenger-name').toLowerCase();
            const journeyId = row.getAttribute('data-journey-id');
            const journeyFilterValue = journeyFilter.value;

            // Check if row matches both search and journey filter
            const matchesSearch = passengerName.includes(searchTerm);
            const matchesJourney = journeyFilterValue === '' || journeyId === journeyFilterValue;

            row.style.display = (matchesSearch && matchesJourney) ? '' : 'none';
        });
    });

    // Filter by journey ID
    journeyFilter.addEventListener('change', function() {
        const selectedJourney = this.value;
        const searchTerm = passengerSearch.value.toLowerCase();

        passengerRows.forEach(row => {
            const journeyId = row.getAttribute('data-journey-id');
            const passengerName = row.getAttribute('data-passenger-name').toLowerCase();

            // Check if row matches both search and journey filter
            const matchesJourney = selectedJourney === '' || journeyId === selectedJourney;
            const matchesSearch = passengerName.includes(searchTerm);

            row.style.display = (matchesJourney && matchesSearch) ? '' : 'none';
        });
    });

    // Export functionality
    exportBtn.addEventListener('click', function() {
        exportModal.show();
    });

    confirmExportBtn.addEventListener('click', function() {
        const format = document.getElementById('export-format').value;
        const includeJourneyId = document.getElementById('include-journey-id').checked;
        const includePassengerName = document.getElementById('include-passenger-name').checked;
        const includeRoute = document.getElementById('include-route').checked;
        const includeDateTime = document.getElementById('include-date-time').checked;

        // Get visible rows only (filtered results)
        const visibleRows = Array.from(passengerRows).filter(row => row.style.display !== 'none');

        // Prepare data for export
        const exportData = [];
        const headers = [];

        if (includeJourneyId) headers.push('Journey ID');
        if (includePassengerName) headers.push('Passenger Name');
        if (includeRoute) headers.push('Route');
        if (includeDateTime) headers.push('Date', 'Time');

        exportData.push(headers);

        visibleRows.forEach(row => {
            const rowData = [];
            if (includeJourneyId) rowData.push(row.getAttribute('data-journey-id'));
            if (includePassengerName) rowData.push(row.getAttribute('data-passenger-name'));
            if (includeRoute) {
                const routeElement = row.querySelector('td:nth-child(3) .badge');
                rowData.push(routeElement ? routeElement.textContent.trim() : '');
            }
            if (includeDateTime) {
                const dateTimeElement = row.querySelector('td:nth-child(4) .text-muted');
                const dateText = dateTimeElement ? dateTimeElement.textContent.split('\n')[0].replace(/[^\d-]/g, '').trim() : '';
                const timeText = dateTimeElement ? dateTimeElement.textContent.split('\n')[1].replace(/[^\d:]/g, '').trim() : '';
                rowData.push(dateText, timeText);
            }
            exportData.push(rowData);
        });

        // Export based on format
        if (format === 'csv') {
            exportToCSV(exportData);
        } else if (format === 'excel') {
            exportToExcel(exportData);
        } else if (format === 'pdf') {
            exportToPDF(exportData);
        }

        exportModal.hide();
    });

    // CSV Export Function
    function exportToCSV(data) {
        let csvContent = "data:text/csv;charset=utf-8,";

        data.forEach(row => {
            const csvRow = row.join(',');
            csvContent += csvRow + '\r\n';
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "passengers_export.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Excel Export Function
    function exportToExcel(data) {
        try {
            // Create a workbook with a worksheet
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);

            // Add the worksheet to the workbook
            XLSX.utils.book_append_sheet(wb, ws, "Passengers");

            // Generate Excel file and trigger download
            XLSX.writeFile(wb, "passengers_export.xlsx");
        } catch (error) {
            console.error("Excel export error:", error);
            alert("Error exporting to Excel. Please make sure the SheetJS library is properly loaded.");
        }
    }

    // PDF Export Function
    function exportToPDF(data) {
        try {
            // Initialize jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add title
            doc.setFontSize(18);
            doc.text("Passenger List", 14, 22);

            // Add export date
            doc.setFontSize(11);
            doc.text("Exported on: " + new Date().toLocaleString(), 14, 30);

            // Create the table
            doc.autoTable({
                head: [data[0]],
                body: data.slice(1),
                startY: 35,
                styles: {
                    fontSize: 10,
                    cellPadding: 3,
                    lineColor: [44, 62, 80],
                    lineWidth: 0.25
                },
                headStyles: {
                    fillColor: [41, 128, 185],
                    textColor: 255,
                    fontStyle: 'bold'
                },
                alternateRowStyles: {
                    fillColor: [242, 242, 242]
                },
                margin: { top: 35 }
            });

            // Save the PDF
            doc.save("passengers_export.pdf");
        } catch (error) {
            console.error("PDF export error:", error);
            alert("Error exporting to PDF. Please make sure the jsPDF library is properly loaded.");
        }
    }
});
</script>

@endsection
