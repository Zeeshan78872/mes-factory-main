@extends('layouts.app')
@section('content')

<style>
    .dash-icons {
        font-size: 25px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row text-center dash-icons">
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('customers.index') }}">
                                <i class="fas fa-2x fa-users"></i>
                                <br>
                                Customers
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('job-orders.index') }}">
                                <i class="fas fa-2x fa-cart-plus"></i>
                                <br>
                                Job orders
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('job-order.bom.index') }}">
                                <i class="fas fa-2x fa-luggage-cart"></i>
                                <br>
                                Manage BOM
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('job-order.purchase.index') }}">
                                <i class="fas fa-2x fa-cart-plus"></i>
                                <br>
                                Manage Purchase
                            </a>
                        </div>
                    </div>

                    <div class="row text-center dash-icons mt-5">
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('job-order.receiving.index') }}">
                                <i class="fas fa-2x fa-clipboard-check"></i>
                                <br>
                                Manage Receiving
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('stock-cards.index') }}">
                                <i class="fas fa-2x fa-print"></i>
                                <br>
                                Stock Cards
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('inventory.list.for.production') }}">
                                <i class="fas fa-2x fa-pallet"></i>
                                <br>
                                Issue for Production
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('inventory.audit-items') }}">
                                <i class="fas fa-2x fa-cash-register"></i>
                                <br>
                                Audit
                            </a>
                        </div>
                    </div>

                    <div class="row text-center dash-icons mt-5">
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('productions.list') }}">
                                <i class="fas fa-2x fa-list"></i>
                                <br>
                                Production Lists
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('production.daily') }}">
                                <i class="fas fa-2x fa-tractor"></i>
                                <br>
                                Daily Production
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('quality-assurance.index') }}">
                                <i class="fas fa-2x fa-tasks"></i>
                                <br>
                                QC Lists
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('quality-assurance.report') }}">
                                <i class="fas fa-2x fa-tasks"></i>
                                <br>
                                QC Report
                            </a>
                        </div>
                    </div>

                    <div class="row text-center dash-icons mt-5">
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('job-orders.shipping.report') }}">
                                <i class="fas fa-2x fa-clipboard-list"></i>
                                <br>
                                Shipping Schedule
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('shippings.index') }}">
                                <i class="fas fa-2x fa-dolly"></i>
                                <br>
                                Shipping List
                            </a>
                        </div>
                        <div class="mt-5 col-md-3">
                            <a href="{{ route('shippings.scan-qr') }}">
                                <i class="fas fa-2x fa-stopwatch"></i>
                                <br>
                                Record Scan Progress
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
