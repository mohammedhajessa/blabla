@extends('partial.main')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
    });
</script>

<!-- Add notification container for passengers -->
@if(Auth::guard('passenger')->check())
<div class="notification-container position-fixed top-0 end-0 p-3" style="z-index: 1080; max-width: 400px;">
    <div id="passenger-notifications"></div>
</div>

<script>
    // Set up Pusher listener for passenger notifications
    document.addEventListener('DOMContentLoaded', function() {
        // Subscribe to the passenger's channel
        const passengerChannel = pusher.subscribe('passenger-{{ Auth::guard('passenger')->id() }}');

        // Listen for booking responses
        passengerChannel.bind('booking-response', function(data) {
            console.log('Received booking response:', data);

            // Create notification element
            const notificationId = 'notification-' + Date.now();
            const statusColor = data.status === 'accepted' ? 'success' : 'danger';
            const statusIcon = data.status === 'accepted' ? 'bx-check-circle' : 'bx-x-circle';
            const statusText = data.status === 'accepted' ? 'accepted' : 'rejected';

            const notificationHtml = `
                <div id="${notificationId}" class="toast show align-items-center text-white bg-${statusColor} border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bx ${statusIcon} me-2"></i>
                            <strong>Journey Request ${statusText.toUpperCase()}</strong><br>
                            ${data.message}<br>
                            <small class="text-white-50">Journey ID: ${data.journey_id}</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            // Add notification to container
            document.getElementById('passenger-notifications').innerHTML += notificationHtml;

            // Play notification sound if available
            try {
                const audio = new Audio('/assets/sounds/notification.mp3');
                audio.play().catch(e => console.warn('Could not play notification sound:', e));
            } catch (e) {
                console.warn('Error playing notification sound:', e);
            }

            // Auto-remove notification after 10 seconds
            setTimeout(() => {
                const notification = document.getElementById(notificationId);
                if (notification) {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            }, 10000);

            // Update the journey table if it exists
            refreshJourneyDisplay();
        });
    });
</script>
@endif

<!-- Hero: Start -->
        <section id="hero-animation">
            <div id="landingHero" class="section-py landing-hero position-relative">
                <img src="{{ asset('assets/img/front-pages/backgrounds/hero-bg.png') }}" alt="hero background"
                    class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100"
                    data-speed="1" />
                <div class="container">
                    <div class="hero-text-box text-center position-relative">
                        <h1 class="text-primary hero-title display-6 fw-extrabold">
                            Connect Through Shared Journeys
                        </h1>
                        <h2 class="hero-sub-title h6 mb-6">
                            More than just a ride - build community on the go<br class="d-none d-lg-block" />
                            Reduce costs, make meaningful connections, and travel sustainably.
                        </h2>
                        <div class="landing-hero-btn d-inline-block position-relative">
                            <span class="hero-btn-item position-absolute d-none d-md-flex fw-medium">
                                Join our movement
                                <img src="{{ asset('assets/img/front-pages/icons/Join-community-arrow.png') }}"
                                    alt="Join community arrow" class="scaleX-n1-rtl" />
                            </span>
                            <a href="#landingJourneys" class="btn btn-primary btn-lg">Discover Journeys</a>
                        </div>
                    </div>
                    <div id="heroDashboardAnimation" class="hero-animation-img">
                        <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
                            <img src="{{ asset('assets/img/front-pages/landing-page/hero-dashboard-light.png') }}"
                                alt="hero dashboard" class="animation-img"
                                data-app-light-img="front-pages/landing-page/hero-dashboard-light.png"
                                data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="landing-hero-blank"></div>
        </section>
        <!-- Hero: End -->

        <!-- Journey Search: Start -->
        <section id="landingJourneys" class="section-py landing-journeys">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Explore Connections</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">
                        Find Your Community on the Road
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}"
                            alt="section title icon"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                </h4>
                <p class="text-center mb-5">Connect with like-minded travelers and transform your daily commute into meaningful experiences</p>

                <div class="card mb-5">
                    <div class="card-body">
                        <form id="searchJourneyForm" class="row g-3">
                            <div class="col-md-4">
                                <label for="pickup_city" class="form-label">Starting Point</label>
                                <select id="pickup_city" name="pickup_city" class="form-select">
                                    <option value="">Select City</option>
                                    @foreach($cities ?? [] as $city)
                                        <option value="{{ $city->id }}" {{ old('pickup_city_id') == $city->id ? 'selected' : '' }}>
                                            {{ strtoupper(ucfirst($city->name)) }}
                                            @if($city->region)
                                                ({{ strtoupper(ucfirst($city->region->name)) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            @error('pickup_city_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="dropoff_city" class="form-label">Destination</label>
                                <select id="dropoff_city" name="dropoff_city" class="form-select">
                                    <option value="">Select City</option>
                                    @foreach($cities ?? [] as $city)
                                        <option value="{{ $city->id }}" {{ old('dropoff_city_id') == $city->id ? 'selected' : '' }}>
                                            {{ strtoupper(ucfirst($city->name)) }}
                                            @if($city->region)
                                                ({{ strtoupper(ucfirst($city->region->name)) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="journey_date" class="form-label">When</label>
                                <input type="date" class="form-control" id="journey_date" name="journey_date"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </form>

                        <div id="journeyResults" class="mt-4"></div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Add event listeners for real-time search
                                document.getElementById('pickup_city').addEventListener('change', searchJourneys);
                                document.getElementById('dropoff_city').addEventListener('change', searchJourneys);
                                document.getElementById('journey_date').addEventListener('change', searchJourneys);

                                // Set up real-time updates
                                let journeyUpdateInterval;

                                // Check for pending journey requests on page load (for real-time status)
                                @if(Auth::guard('passenger')->check())
                                checkPendingJourneyRequests();

                                // Set up interval to periodically check for pending requests
                                setInterval(checkPendingJourneyRequests, 60000); // Check every minute
                                @endif

                                function searchJourneys() {
                                    const pickup_city = document.getElementById('pickup_city').value;
                                    const dropoff_city = document.getElementById('dropoff_city').value;
                                    const journey_date = document.getElementById('journey_date').value;

                                    // Only search if we have at least pickup and dropoff
                                    if (!pickup_city || !dropoff_city) {
                                        return;
                                    }

                                    // Show loading indicator
                                    const resultsContainer = document.getElementById('journeyResults');
                                    resultsContainer.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

                                    // Fetch results
                                    fetchJourneyData(pickup_city, dropoff_city, journey_date);

                                    // Clear any existing interval
                                    if (journeyUpdateInterval) {
                                        clearInterval(journeyUpdateInterval);
                                    }

                                    // Set up real-time updates every 30 seconds
                                    journeyUpdateInterval = setInterval(function() {
                                        fetchJourneyData(pickup_city, dropoff_city, journey_date, true);
                                    }, 30000);
                                }

                                function fetchJourneyData(pickup_city, dropoff_city, journey_date, isUpdate = false) {
                                    // Add a timestamp to prevent caching
                                    const timestamp = new Date().getTime();

                                    fetch(`{{ route('frontpage.searchJourneys') }}?pickup_city=${pickup_city}&dropoff_city=${dropoff_city}&journey_date=${journey_date}&_=${timestamp}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            displayResults(data, isUpdate);
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            if (!isUpdate) {
                                                const resultsContainer = document.getElementById('journeyResults');
                                            resultsContainer.innerHTML = '<div class="alert alert-danger">An error occurred while searching. Please try again.</div>';
                                            }
                                        });
                                }

                                function displayResults(journeys, isUpdate = false) {
                                    const resultsContainer = document.getElementById('journeyResults');

                                    // If this is an update and there are no results, don't clear the existing display
                                    if (isUpdate && journeys.length === 0) {
                                        return;
                                    }

                                    if (journeys.length === 0) {
                                        resultsContainer.innerHTML = '<div class="alert alert-info">No journeys found for the selected criteria. Why not be the first to create one?</div>';
                                        return;
                                    }

                                    let html = `
                                    <div class="card-datatable table-responsive">
                                        <table class="datatables-journeys table">
                                            <thead class="border-top">
                                                <tr>
                                                    <th>Community Member</th>
                                                    <th>Connection Path</th>
                                                    <th>When</th>
                                                    <th>Open Spots</th>
                                                    <th>Contribution</th>
                                                    <th>Join</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                                    journeys.forEach(journey => {
                                        const driverName = journey.driver && journey.driver.name ? journey.driver.name : 'Community Member';
                                        const pickupCity = journey.pickup_city_name || journey.pickup_city_id || 'Starting Point';
                                        const dropoffCity = journey.dropoff_city_name || journey.dropoff_city_id || 'Destination';

                                        // Check if this journey is already booked by the user
                                        let isBooked = journey.is_booked || false;

                                        // Also check localStorage for pending requests
                                        if (!isBooked) {
                                            isBooked = isJourneyBooked(journey.id);
                                        }

                                        // Determine button state and text
                                        let buttonClass, buttonText, isDisabled;

                                        if (isBooked) {
                                            buttonClass = 'btn-warning disabled';
                                            buttonText = 'Request Pending';
                                            isDisabled = true;
                                        } else if (journey.available_seats <= 0) {
                                            buttonClass = 'btn-secondary disabled';
                                            buttonText = 'Fully Booked';
                                            isDisabled = true;
                                        } else {
                                            buttonClass = 'btn-primary';
                                            buttonText = 'Connect Now';
                                            isDisabled = false;
                                        }

                                        // Add real-time seat availability indicator
                                        const seatClass = journey.available_seats <= 1 ? 'bg-label-danger' :
                                                         journey.available_seats <= 3 ? 'bg-label-warning' : 'bg-label-success';

                                        html += `
                                            <tr data-journey-id="${journey.id}">
                                                <td><span class="badge bg-label-primary">${driverName}</span></td>
                                                <td><span class="badge bg-label-primary">${pickupCity} to ${dropoffCity}</span></td>
                                                <td><span class="badge bg-label-warning">${journey.journey_date} - ${journey.pickup_time}</span></td>
                                                <td><span class="badge ${seatClass} seat-count" data-seats="${journey.available_seats}">${journey.available_seats} spot${journey.available_seats !== 1 ? 's' : ''}</span></td>
                                                <td><span class="badge bg-label-success">${journey.price} tr</span></td>
                                                <td>
                                                    <button type="button" class="btn ${buttonClass} book-journey-btn"
                                                        data-journey-id="${journey.id}"
                                                        ${isDisabled ? 'disabled' : ''}>
                                                        ${buttonText}
                                                    </button>
                                                </td>
                                            </tr>`;
                                    });

                                    html += `
                                            </tbody>
                                        </table>
                                        <div class="text-end text-muted small mt-2">Last updated: ${new Date().toLocaleTimeString()}</div>
                                    </div>`;

                                    // If this is an update, only update the seat counts and availability
                                    if (isUpdate && document.querySelector('.datatables-journeys')) {
                                        journeys.forEach(journey => {
                                            const row = document.querySelector(`tr[data-journey-id="${journey.id}"]`);
                                            if (row) {
                                                // Update seat count
                                                const seatSpan = row.querySelector('.seat-count');
                                                if (seatSpan) {
                                                    const currentSeats = parseInt(seatSpan.getAttribute('data-seats'));
                                                    if (currentSeats !== journey.available_seats) {
                                                        const seatClass = journey.available_seats <= 1 ? 'bg-label-danger' :
                                                                         journey.available_seats <= 3 ? 'bg-label-warning' : 'bg-label-success';
                                                        seatSpan.className = `badge ${seatClass} seat-count`;
                                                        seatSpan.setAttribute('data-seats', journey.available_seats);
                                                        seatSpan.textContent = `${journey.available_seats} spot${journey.available_seats !== 1 ? 's' : ''}`;

                                                        // Flash the updated cell
                                                        seatSpan.classList.add('bg-flash');
                                                        setTimeout(() => {
                                                            seatSpan.classList.remove('bg-flash');
                                                        }, 1000);
                                                    }
                                                }

                                                // Update booking status
                                                const bookButton = row.querySelector('.book-journey-btn');
                                                if (bookButton) {
                                                    // Check if already booked (from server or localStorage)
                                                    let isBooked = journey.is_booked || isJourneyBooked(journey.id);

                                                    if (isBooked) {
                                                        bookButton.className = 'btn btn-warning disabled book-journey-btn';
                                                        bookButton.textContent = 'Request Pending';
                                                        bookButton.disabled = true;
                                                    } else if (journey.available_seats <= 0) {
                                                        bookButton.className = 'btn btn-secondary disabled book-journey-btn';
                                                        bookButton.textContent = 'Fully Booked';
                                                        bookButton.disabled = true;
                                                    } else {
                                                        bookButton.className = 'btn btn-primary book-journey-btn';
                                                        bookButton.textContent = 'Connect Now';
                                                        bookButton.disabled = false;
                                                    }
                                                }
                                            }
                                        });

                                        // Update the timestamp
                                        const timestampDiv = document.querySelector('.text-end.text-muted.small');
                                        if (timestampDiv) {
                                            timestampDiv.textContent = `Last updated: ${new Date().toLocaleTimeString()}`;
                                        }
                                    } else {
                                        resultsContainer.innerHTML = html;

                                        // Add event listeners to the newly created buttons
                                        document.querySelectorAll('.book-journey-btn').forEach(button => {
                                            if (!button.disabled) {
                                                button.addEventListener('click', function() {
                                                    const journeyId = this.getAttribute('data-journey-id');
                                                    bookJourney(journeyId);
                                                });
                                            }
                                        });
                                    }
                                }

                                function bookJourney(journeyId) {
                                    console.log('Starting booking process for journey ID:', journeyId);

                                    // Show booking in progress indicator
                                    const button = document.querySelector(`.book-journey-btn[data-journey-id="${journeyId}"]`);
                                    if (button) {
                                        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Booking...';
                                button.disabled = true;
                                    }

                                    // Check if user is logged in as passenger
                                    @if(Auth::guard('passenger')->check())
                                        // Create a form data object for more reliable submission
                                        const formData = new FormData();
                                        formData.append('journey_id', journeyId);
                                        formData.append('_token', '{{ csrf_token() }}');

                                        console.log('Sending booking request to server...');

                                        // Use fetch but with form data instead of JSON
                                        fetch('{{ route("frontpage.bookJourney") }}', {
                                    method: 'POST',
                                            body: formData,
                                    headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then(response => {
                                            console.log('Server response status:', response.status);
                                            return response.json().catch(error => {
                                                // Handle non-JSON responses
                                                console.error('Error parsing JSON:', error);
                                                throw new Error('Invalid server response');
                                            });
                                        })
                                .then(data => {
                                            console.log('Server response data:', data);

                                    if (data.success) {
                                                // Update button to show success
                                                if (button) {
                                                    button.className = 'btn btn-warning disabled book-journey-btn';
                                                    button.innerHTML = 'Request Pending';
                                                    button.disabled = true;
                                                }

                                        // Show success message
                                                showToast('success', 'Journey request sent!', 'Your request has been sent to the driver. You will be notified when they respond.');

                                                // Store booked journeys in localStorage
                                                storeBookedJourney(journeyId);

                                                // Refresh the journey display
                                                setTimeout(refreshJourneyDisplay, 1000);
                                            } else {
                                                // Check if it's already booked
                                                if (data.is_booked) {
                                                    if (button) {
                                                        button.className = 'btn btn-warning disabled book-journey-btn';
                                                        button.innerHTML = 'Request Pending';
                                                        button.disabled = true;
                                                    }

                                                    // Store booked journeys in localStorage
                                                    storeBookedJourney(journeyId);

                                                    showToast('warning', 'Already Requested', data.message || 'You have already requested this journey. Please wait for the driver to respond.');
                                    } else {
                                                    // Handle error
                                                    if (button) {
                                                        button.className = 'btn btn-danger book-journey-btn';
                                                        button.innerHTML = 'Try Again';
                                                        button.disabled = false;
                                                    }
                                                    showToast('danger', 'Booking Failed', data.message || 'Failed to book journey. Please try again.');
                                                }
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error in fetch operation:', error);
                                            if (button) {
                                                button.className = 'btn btn-danger book-journey-btn';
                                                button.innerHTML = 'Try Again';
                                        button.disabled = false;
                                            }
                                            showToast('danger', 'Error', 'An error occurred while sending your request. Please try again.');
                                        });
                                    @else
                                        // Redirect to login page if not logged in
                                        window.location.href = '{{ route("passenger.login") }}?redirect=frontpage.index';
                                    @endif
                                }

                                // Function to store booked journeys in localStorage
                                function storeBookedJourney(journeyId) {
                                    try {
                                        const userId = "{{ Auth::guard('passenger')->id() }}";
                                        const storageKey = 'bookedJourneys_' + userId;
                                        let bookedJourneys = JSON.parse(localStorage.getItem(storageKey)) || [];
                                        if (!bookedJourneys.includes(journeyId)) {
                                            bookedJourneys.push(journeyId);
                                            localStorage.setItem(storageKey, JSON.stringify(bookedJourneys));
                                        }
                                    } catch (e) {
                                        console.warn('Failed to store booked journey in localStorage:', e);
                                    }
                                }

                                // Function to check if a journey is already booked
                                function isJourneyBooked(journeyId) {
                                    try {
                                        const userId = "{{ Auth::guard('passenger')->id() }}";
                                        const storageKey = 'bookedJourneys_' + userId;
                                        let bookedJourneys = JSON.parse(localStorage.getItem(storageKey)) || [];
                                        return bookedJourneys.includes(journeyId);
                                    } catch (e) {
                                        console.warn('Failed to check booked journey in localStorage:', e);
                                        return false;
                                    }
                                }

                                // Function to clear booked journeys for a user
                                function clearBookedJourneys() {
                                    try {
                                        const userId = "{{ Auth::guard('passenger')->id() }}";
                                        const storageKey = 'bookedJourneys_' + userId;
                                        localStorage.removeItem(storageKey);
                                        console.log('Cleared booked journeys for user:', userId);
                                    } catch (e) {
                                        console.warn('Failed to clear booked journeys:', e);
                                    }
                                }

                                // Function to show toast notifications
                                function showToast(type, title, message) {
                                    const toastContainer = document.getElementById('passenger-notifications');
                                    if (!toastContainer) return;

                                    const notificationId = 'notification-' + Date.now();
                                    const html = `
                                        <div id="${notificationId}" class="toast show align-items-center text-white bg-${type} border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="d-flex">
                                                <div class="toast-body">
                                                    <strong>${title}</strong><br>
                                                    ${message}
                                                </div>
                                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    `;

                                    toastContainer.innerHTML += html;

                                    // Auto-remove notification after 5 seconds
                                    setTimeout(() => {
                                        const notification = document.getElementById(notificationId);
                                        if (notification) {
                                            notification.classList.remove('show');
                                            setTimeout(() => {
                                                notification.remove();
                                            }, 300);
                                        }
                                    }, 5000);
                                }

                                // Function to refresh journey display with updated data
                                function refreshJourneyDisplay() {
                                    const pickup_city = document.getElementById('pickup_city')?.value;
                                    const dropoff_city = document.getElementById('dropoff_city')?.value;
                                    const journey_date = document.getElementById('journey_date')?.value;

                                    if (pickup_city && dropoff_city) {
                                        fetchJourneyData(pickup_city, dropoff_city, journey_date, true);
                                    }
                                }

                                // Function to check pending journey requests
                                function checkPendingJourneyRequests() {
                                    @if(Auth::guard('passenger')->check())
                                    fetch('{{ route("frontpage.checkPendingRequests") }}', {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.pending_requests && data.pending_requests.length > 0) {
                                            // Store all pending requests in localStorage
                                            data.pending_requests.forEach(request => {
                                                storeBookedJourney(request.journey_id);
                                            });

                                            // Refresh the journey display if there are journeys displayed
                                            if (document.querySelector('.datatables-journeys')) {
                                                refreshJourneyDisplay();
                                            }
                                    }
                                })
                                .catch(error => {
                                        console.error('Error checking pending requests:', error);
                                    });
                                    @endif
                                }

                                // Add CSS for seat update animation
                                const style = document.createElement('style');
                                style.textContent = `
                                    .bg-flash {
                                        animation: flash-animation 1s;
                                    }
                                    @keyframes flash-animation {
                                        0% { opacity: 1; }
                                        50% { opacity: 0.5; background-color: #ffeb3b; }
                                        100% { opacity: 1; }
                                    }
                                `;
                                document.head.appendChild(style);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </section>
        <!-- Journey Search: End -->

        <!-- Features: Start -->
        <section id="landingFeatures" class="section-py landing-features bg-body">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Our Community Values</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">
                        Beyond Transportation: Building Connections
                        <img src="../../assets/img/front-pages/icons/section-title-icon.png" alt="section title icon"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                </h4>
                <p class="text-center mb-12">
                    Join thousands who are transforming everyday travel into meaningful experiences
                </p>
                <div class="features-icon-wrapper row gx-0 gy-6 g-sm-12">
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <img src="../../assets/img/front-pages/icons/check.png" alt="shared economy" />
                        </div>
                        <h5 class="mb-2">Shared Economy</h5>
                        <p class="features-icon-description">
                            Participate in a collaborative economy where resources are shared for mutual benefit
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <img src="../../assets/img/front-pages/icons/rocket.png" alt="meaningful connections" />
                        </div>
                        <h5 class="mb-2">Meaningful Connections</h5>
                        <p class="features-icon-description">
                            Transform travel time into opportunities for conversation and relationship building
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <img src="../../assets/img/front-pages/icons/user.png" alt="community building" />
                        </div>
                        <h5 class="mb-2">Community Building</h5>
                        <p class="features-icon-description">
                            Create networks of support and friendship through regular shared journeys
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <img src="../../assets/img/front-pages/icons/paper.png" alt="sustainability" />
                        </div>
                        <h5 class="mb-2">Sustainability</h5>
                        <p class="features-icon-description">
                            Be part of the solution by reducing carbon footprints through shared transportation
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <img src="../../assets/img/front-pages/icons/keyboard.png" alt="trust and safety" />
                        </div>
                        <h5 class="mb-2">Trust and Safety</h5>
                        <p class="features-icon-description">
                            Join a community built on mutual respect, verification, and transparent reviews
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-4">
                            <img src="../../assets/img/front-pages/icons/laptop.png" alt="accessibility" />
                        </div>
                        <h5 class="mb-2">Accessibility</h5>
                        <p class="features-icon-description">
                            Make transportation more accessible to everyone through our inclusive platform
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features: End -->

        <!-- FAQ: Start -->
        <section id="landingFAQ" class="section-py landing-faq">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Community Questions</span>
                </div>
                <h4 class="text-center mb-1">
                    Common
                    <span class="position-relative fw-extrabold z-1">
                        questions
                        <img src="../../assets/img/front-pages/icons/section-title-icon.png" alt="section title icon"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                </h4>
                <p class="text-center mb-12 pb-md-4">
                    Everything you need to know about joining our journey-sharing community.
                </p>
                <div class="row gy-12 align-items-center">
                    <div class="col-lg-5">
                        <div class="text-center">
                            <img src="../../assets/img/front-pages/landing-page/faq-boy-with-logos.png"
                                alt="faq illustration" class="faq-image" />
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="accordion" id="accordionExample">
                            <div class="card accordion-item active">
                                <h2 class="accordion-header" id="headingOne">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#accordionOne" aria-expanded="true"
                                        aria-controls="accordionOne">
                                        How do I join a journey?
                                    </button>
                                </h2>
                                <div id="accordionOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        To join a journey, search for your desired route and date, browse available
                                        connections, and click Connect Now on the journey that matches your needs. You ll need
                                        to be logged in to complete the connection process.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button type="button" class="accordion-button collapsed"
                                        data-bs-toggle="collapse" data-bs-target="#accordionTwo"
                                        aria-expanded="false" aria-controls="accordionTwo">
                                        How does the contribution system work?
                                    </button>
                                </h2>
                                <div id="accordionTwo" class="accordion-collapse collapse"
                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Our platform operates on a fair contribution model. Passengers contribute to the journey costs,
                                        creating a sustainable sharing economy. We recommend discussing contribution methods
                                        before the journey to ensure a smooth experience for everyone.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button type="button" class="accordion-button collapsed"
                                        data-bs-toggle="collapse" data-bs-target="#accordionThree"
                                        aria-expanded="false" aria-controls="accordionThree">
                                        Can I change my plans?
                                    </button>
                                </h2>
                                <div id="accordionThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Yes, we understand plans change. You can modify your journey participation through your dashboard.
                                        We encourage communicating changes as early as possible out of respect for the community.
                                        Our platform values reliability and mutual consideration.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button type="button" class="accordion-button collapsed"
                                        data-bs-toggle="collapse" data-bs-target="#accordionFour"
                                        aria-expanded="false" aria-controls="accordionFour">
                                        How do I offer my own journeys?
                                    </button>
                                </h2>
                                <div id="accordionFour" class="accordion-collapse collapse"
                                    aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        To share your journeys with the community, click on the Offer a Journey link and complete the
                                        simple process. You ll provide details about your route, available spots, and preferred
                                        contribution. Our platform makes it easy to build community through shared travel.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button type="button" class="accordion-button collapsed"
                                        data-bs-toggle="collapse" data-bs-target="#accordionFive"
                                        aria-expanded="false" aria-controls="accordionFive">
                                        How do you ensure community safety?
                                    </button>
                                </h2>
                                <div id="accordionFive" class="accordion-collapse collapse"
                                    aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Community safety is our priority. We implement verification processes, community ratings,
                                        and transparent reviews. Your personal information is protected, and we only share what s
                                        necessary to facilitate connections. Our community guidelines promote respect and consideration.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ: End -->

        <!-- CTA: Start -->
        <section id="landingCTA" class="section-py landing-cta position-relative p-lg-0 pb-0">
            <img src="../../assets/img/front-pages/backgrounds/cta-bg-light.png"
                class="position-absolute bottom-0 end-0 scaleX-n1-rtl h-100 w-100 z-n1" alt="cta image"
                data-app-light-img="front-pages/backgrounds/cta-bg-light.png"
                data-app-dark-img="front-pages/backgrounds/cta-bg-dark.png" />
            <div class="container">
                <div class="row align-items-center gy-12">
                    <div class="col-lg-6 text-start text-sm-center text-lg-start">
                        <h3 class="cta-title text-primary fw-bold mb-0">Ready to Transform Your Travel Experience?</h3>
                        <h5 class="text-body mb-8">Join our community of connected travelers today</h5>
                        @if (!Auth::guard('passenger')->check())
                            <a href="{{ route('passenger.register') }}" class="btn btn-lg btn-primary">Join Our Community</a>
                        @else
                            <a href="#landingJourneys" class="btn btn-lg btn-primary">Find Your Connection</a>
                        @endif
                    </div>
                    <div class="col-lg-6 pt-lg-12 text-center text-lg-end">
                        <img src="../../assets/img/front-pages/landing-page/cta-dashboard.png" alt="cta dashboard"
                            class="img-fluid mt-lg-4" />
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA: End -->

        <!-- Contact Us: Start -->
        <section id="landingContact" class="section-py bg-body landing-contact">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="badge bg-label-primary">Connect With Us</span>
                </div>
                <h4 class="text-center mb-1">
                    <span class="position-relative fw-extrabold z-1">
                        We re Here For You
                        <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}"
                            alt="section title icon"
                            class="section-title-img position-absolute object-fit-contain bottom-0 z-n1" />
                    </span>
                </h4>
                <p class="text-center mb-5">Have questions, ideas, or feedback? Our community support team is ready to help!</p>

                <div class="row gy-4">
                    <div class="col-lg-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-4">Community Support</h5>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ti ti-mail text-primary me-2"></i>
                                    <span>community@journeyshare.com</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ti ti-phone-call text-primary me-2"></i>
                                    <span>+1 (555) 123-4567</span>
                                </div>
                                <div class="d-flex align-items-center mb-4">
                                    <i class="ti ti-map-pin text-primary me-2"></i>
                                    <span>123 Community Way, Connection City, CC 10101</span>
                                </div>

                                <h5 class="mb-4">Join Our Social Community</h5>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-icon btn-label-facebook"><i
                                            class="ti ti-brand-facebook"></i></a>
                                    <a href="#" class="btn btn-icon btn-label-twitter"><i
                                            class="ti ti-brand-twitter"></i></a>
                                    <a href="#" class="btn btn-icon btn-label-instagram"><i
                                            class="ti ti-brand-instagram"></i></a>
                                    <a href="#" class="btn btn-icon btn-label-linkedin"><i
                                            class="ti ti-brand-linkedin"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-body">
                                <form id="contactForm">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="contactName" class="form-label">Your Name</label>
                                            <input type="text" class="form-control" id="contactName"
                                                placeholder="Jane Doe">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="contactEmail" class="form-label">Your Email</label>
                                            <input type="email" class="form-control" id="contactEmail"
                                                placeholder="jane@example.com">
                                        </div>
                                        <div class="col-12">
                                            <label for="contactSubject" class="form-label">What s on your mind?</label>
                                            <input type="text" class="form-control" id="contactSubject"
                                                placeholder="I d like to share an idea...">
                                        </div>
                                        <div class="col-12">
                                            <label for="contactMessage" class="form-label">Your Message</label>
                                            <textarea class="form-control" id="contactMessage" rows="4" placeholder="Tell us more about your thoughts..."></textarea>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary">Send to Our Community Team</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact Us: End -->
@endsection
