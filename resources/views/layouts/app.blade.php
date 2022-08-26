<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('assets/bootstrap-5.0.1/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/font-awesome-5.15.3/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/style.css') }}" rel="stylesheet">
    
    @yield('custom_css')

    <style>
        .overlay{
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999999;
            background: rgba(255,255,255,0.8);
            text-align: center;
            color: #EEE;
            background: rgba(0,0,0,0.7);
        }
        /* Turn off scrollbar when body element has the loading class */
        body.loading{
            overflow: hidden;   
        }
        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay{
            display: block;
        }
        body.loading .overlay i {
            margin-top: 50vh;
        }


        /* Mega Menu CSS */
        .dropdown-menu.megamenu.show {
            box-shadow: 0px 7px 15px 0px #ccc;
        }
        .col-megamenu .title {
            font-size: 20px !important;
            border-bottom: 1px solid #eee;
        }
        .col-megamenu a {
            text-decoration: none;
            color: rgb(77, 49, 175);
        }
        .col-megamenu a:hover {
            color: rgb(120, 89, 231);
        }rgb(88, 54, 212)
        .navbar .megamenu{ padding: 1rem; }

        /* ============ desktop view ============ */
        @media all and (min-width: 992px) {

        .navbar .has-megamenu{position:static!important;}
        .navbar .megamenu{left:0; right:0; width:100%; margin-top:0;  }

        }	
        /* ============ desktop view .end// ============ */

        /* ============ mobile view ============ */
        @media(max-width: 991px){
            .navbar.fixed-top .navbar-collapse, .navbar.sticky-top .navbar-collapse{
                overflow-y: auto;
                max-height: 90vh;
                margin-top:10px;
            }
        }
        /* ============ mobile view .end// ============ */
    </style>
</head>
<body>
    <div id="app">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}"><i class="fas fa-industry"></i> {{ config('app.name', 'SIMEWOOD MES')
                    }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main_nav">
                    @auth
                    <ul class="navbar-nav">
                        {{-- Management Mega Menu --}}
                        <li class="nav-item dropdown has-megamenu">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> Management </a>
                            <div class="dropdown-menu megamenu" role="menu">
                                <div class="row g-3 ps-3">
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">Marketing</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('customers.index') }}"><i class="fas fa-users"></i> Customers</a></li>
                                                <li><a href="{{ route('job-orders.index') }}"><i class="fas fa-cart-plus"></i> Manage Orders</a></li>
                                                <li><a href="{{ route('job-orders.orders.list.report') }}"><i class="fas fa-clipboard-list"></i> Orders List Report</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-md -->
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">R&D</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('job-order.bom.index') }}"><i class="fas fa-luggage-cart"></i> Manage BOM</a></li>
                                                <li><a href="{{ route('products.index') }}"><i class="fas fa-cubes"></i> Manage Products</a></li>
                                                <li><a href="{{ route('product.categories.index') }}"><i class="fas fa-tags"></i> Manage Categories</a></li>
                                                <li><a href="{{ route('product.units.index') }}"><i class="fas fa-superscript"></i> Manage Units</a></li>
                                                <li><a href="{{ route('materials.index') }}"><i class="fas fa-microchip"></i> Manage Materials</a></li>
                                                <li><a href="{{ route('colors.index') }}"><i class="fas fa-swatchbook"></i> Manage Colors</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-md -->
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">Purchasing</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('job-order.purchase.index') }}"><i class="fas fa-cart-plus"></i> Manage Purchase</a></li>
                                                <li><a href="{{ route('suppliers.index') }}"><i class="fas fa-users"></i> Manage Suppliers</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div>
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">Store & Warehouse</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('job-order.receiving.index') }}"><i class="fas fa-clipboard-check"></i> Manage Receiving</a></li>
                                                <li><a href="{{ route('stock-cards.index') }}"><i class="fas fa-print"></i> Stock Cards</a></li>
                                                <li><a href="{{ route('inventory.list.for.production') }}"><i class="fas fa-pallet"></i> Issue for Production</a></li>
                                                <li><a href="{{ route('inventory.audit-items') }}"><i class="fas fa-cash-register"></i> Audit</a></li>
                                                <li><a href="{{ route('inventory.reports') }}"><i class="fas fa-clipboard-list"></i> Inventory Reports</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-md -->
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">Costing</h6>
                                            <ul class="list-unstyled">
                                                {{-- <a class="dropdown-item" href="{{ route('costing.reports.daily.production') }}">
                                                    <i class="fas fa-clipboard-list"></i> Report for Production
                                                </a> --}}
                                                <li><a href="{{ route('costing.reports.dashboard') }}"><i class="fas fa-chart-pie"></i> Costing Dashboard</a></li>
                                                <li><a href="{{ route('costing.reports.chemical.usage') }}"><i class="fas fa-clipboard-list"></i> Report for Chemical Usage</a></li>
                                                <li><a href="{{ route('costing.reports.time.summary') }}"><i class="fas fa-clipboard-list"></i> Report for Time Summary</a></li>
                                                <li><a href="{{ route('costing.reports.purchase.cost') }}"><i class="fas fa-clipboard-list"></i> Report for Purchase Cost</a></li>
                                                <li><a href="{{ route('costing.reports.loading.cost') }}"><i class="fas fa-clipboard-list"></i> Report for Loading</a></li>
                                                <li><a href="{{ route('costing.reports.job.order.summary.cost') }}"><i class="fas fa-clipboard-list"></i> Report for Job Order Summary</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-md -->
                                </div><!-- end row -->
                            </div> <!-- dropdown-mega-menu.// -->
                        </li>

                        {{-- Manufacturing Mega Menu --}}
                        <li class="nav-item dropdown has-megamenu">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> Manufacturing </a>
                            <div class="dropdown-menu megamenu" role="menu">
                                <div class="row g-3 ps-3">
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">Production</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('productions.list') }}"><i class="fas fa-list"></i> All Productions List</a></li>
                                                <li><a href="{{ route('production.daily') }}"><i class="fas fa-tractor"></i> Daily Productions</a></li>
                                                <li><a href="{{ route('machines.index') }}"><i class="fas fa-robot"></i> Manage Machines</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-md -->
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">Quality Control</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('quality-assurance.index') }}"><i class="fas fa-tasks"></i> List QC Reports</a></li>
                                                <li><a href="{{ route('quality-assurance.report') }}"><i class="fas fa-tasks"></i> QC Report</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div><!-- end col-md -->
                                    <div class="col-md-2">
                                        <div class="col-megamenu">
                                            <h6 class="title">Shipping</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('job-orders.shipping.report') }}"><i class="fas fa-clipboard-list"></i> Shipping Schedule</a></li>
                                                <li><a href="{{ route('shippings.index') }}"><i class="fas fa-dolly"></i> List Shipping</a></li>
                                                <li><a href="{{ route('shippings.scan-qr') }}"><i class="fas fa-stopwatch"></i> Record Scan Progress</a></li>
                                            </ul>
                                        </div> <!-- col-megamenu.// -->
                                    </div>
                                </div><!-- end row -->
                            </div> <!-- dropdown-mega-menu.// -->
                        </li>

                        {{-- System Settings Menu --}}
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                System Setting
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('system.settings.edit') }}">
                                        <i class="fas fa-cog"></i> Manage System Settings
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.index') }}">
                                        <i class="fas fa-users"></i> Manage Users
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('roles.index') }}">
                                        <i class="fas fa-user-shield"></i> Roles & Permissions
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('site-locations.index') }}">
                                        <i class="fas fa-map-marker-alt"></i> Site Locations
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('departments.index') }}">
                                        <i class="far fa-building"></i> Manage Departments
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('system-logs.index') }}">
                                        <i class="fas fa-clipboard-check"></i> System Logs
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                    
                    {{-- Logout --}}
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link  dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }} <span
                                class="badge bg-primary rounded-pill @if($notificationsCount>0) blinkEffect @endif">{{ $notificationsCount
                                }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-bell"></i> Notifications <span
                                    class="badge bg-primary rounded-pill @if($notificationsCount>0) blinkEffect @endif">{{
                                    $notificationsCount }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endauth
                </div> <!-- navbar-collapse.// -->
            </div> <!-- container-fluid.// -->
        </nav>

        <main class="py-4">

            <div class="container-fluid mt-2 mb-2">
                <div class="row">
                    <div class="col-md-12">
                        @include('includes.errors')
                        @include('includes.success')
                    </div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <br><br><br><br>


    {{-- <footer class="text-center">
        <hr>
        <p>
            All Copyright reserved to T-Robot (TechCapital Resources Sdn Bhd 679831-D)
        </p>
    </footer> --}}

    <!-- Global Ajax Loader -->
    <div class="overlay">
        <i class="fas fa-sync-alt fa-spin fa-7x"></i>
    </div>

    <!-- Scripts -->
    <script>
        var base_url = "{{ url('/') }}";
    </script>
    <script src="{{ asset('assets/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-5.0.1/js/bootstrap.bundle.min.js') }}" defer></script>

    @yield('custom_js')

    <script>
    // Add remove loading class on body element based on Ajax request status
    $(document).on({
        ajaxStart: function(){
            $("body").addClass("loading"); 
        },
        ajaxStop: function(){ 
            $("body").removeClass("loading"); 
        }    
    });

    // keep search input, but avoid autofocus on dropdown open
    $(".select2AutoFocus").on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        }
    );
    </script>
</body>
</html>
