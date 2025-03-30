<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                        fill="#7367F0" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="#7367F0" />
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">BlaBla</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item">
            <ul class="menu-item">
                @if (Auth::check() && Auth::user()->is_admin)
                    <li class="menu-item">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-dashboard"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cars.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-car"></i>
                            <div data-i18n="Cars">Cars</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cities.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-map-pin"></i>
                            <div data-i18n="Cities">Cities</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('drivers.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-users"></i>
                            <div data-i18n="Drivers">Drivers</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('passengers.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-user-circle"></i>
                            <div data-i18n="Passengers">Passengers</div>
                        </a>
                    </li>
                @endif

                @if (Auth::guard('driver')->check())
                    <li class="menu-item">
                        <a href="{{ route('driver.dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-dashboard"></i>
                            <div data-i18n="Driver Dashboard">Driver Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('driver.profile') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-user"></i>
                            <div data-i18n="Profile">Profile</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('driver.cars') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-car"></i>
                            <div data-i18n="My Cars">My Cars</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('journeys.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-map"></i>
                            <div data-i18n="Journeys">Journeys</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('driver.journeyRequests') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-clock"></i>
                            <div data-i18n="Journey Requests">Journey Requests</div>
                            @if (App\Models\JourneyRequest::where('status', 'pending')->wherehas('journey', function ($query) {
                                        $query->where('driver_id', Auth::guard('driver')->user()->id);
                                    })->count() > 0)
                                <span
                                    class="badge bg-danger rounded-pill ms-auto">{{ App\Models\JourneyRequest::where('status', 'pending')->wherehas('journey', function ($query) {
                                            $query->where('driver_id', Auth::guard('driver')->user()->id);
                                        })->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('driver.journeyPassengers') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-users"></i>
                            <div data-i18n="Journey Passengers">Journey Passengers</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('driver.reviews') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-star"></i>
                            <div data-i18n="Reviews">Reviews</div>
                        </a>
                    </li>
                @endif

                @if (Auth::guard('passenger')->check())
                    <li class="menu-item">
                        <a href="{{ route('passenger.dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-dashboard"></i>
                            <div data-i18n="Passenger Dashboard">Passenger Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('passenger.profile') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-user"></i>
                            <div data-i18n="Profile">Profile</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('passenger.journeyRequests') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-clock"></i>
                            <div data-i18n="My Bookings">My Bookings</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('frontpage.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-search"></i>
                            <div data-i18n="Find Journeys">Find Journeys</div>
                        </a>
                    </li>
                @endif
            </ul>
        </li>
    </ul>
</aside>
