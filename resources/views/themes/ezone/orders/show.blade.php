@extends('themes.ezone.layout')

@section('content')
<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210"
    style="background-image: url({{ asset('themes/ezone/assets/img/bg/breadcrumb.jpg') }})">
    <div class="container-fluid">
        <div class="breadcrumb-content text-center">
            <h2>My Favorites</h2>
            <ul>
                <li><a href="{{ url('/') }}">home</a></li>
                <li>my favorites</li>
            </ul>
        </div>
    </div>
</div>
<div class="shop-page-wrapper shop-page-padding ptb-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                @include('themes.ezone.partials.user_menu')
            </div>
            <div class="col-lg-9">
                <div class="d-flex justify-content-between">
                    <h2 class="text-dark font-weight-medium">Order ID #{{ $order->code }}</h2>
                </div>
                <div class="row pt-5">
                    <div class="col-xl-4 col-lg-4">
                        <p class="text-dark mb-2"
                            style="font-weight: normal; font-size:16px; text-transform: uppercase;">Billing Address</p>
                        <address>
                            {{ $order->customer_first_name }} {{ $order->customer_last_name }}
                            <br> {{ $order->customer_address1 }}
                            <br> {{ $order->customer_address2 }}
                            <br> Email: {{ $order->customer_email }}
                            <br> Phone: {{ $order->customer_phone }}
                            <br> Postcode: {{ $order->customer_postcode }}
                        </address>
                    </div>
                    @if ($order->shipment)
                    <div class="col-xl-4 col-lg-4">
                        <p class="text-dark mb-2"
                            style="font-weight: normal; font-size:16px; text-transform: uppercase;">Shipment Address</p>
                        <address>
                            {{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
                            <br> {{ $order->shipment->address1 }}
                            <br> {{ $order->shipment->address2 }}
                            <br> Email: {{ $order->shipment->email }}
                            <br> Phone: {{ $order->shipment->phone }}
                            <br> Postcode: {{ $order->shipment->postcode }}
                        </address>
                    </div>
                    @endif
                    <div class="col-xl-4 col-lg-4">
                        <p class="text-dark mb-2"
                            style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
                        <address>
                            ID: <span class="text-dark">#{{ $order->code }}</span>
                            <br> {{ \General::datetimeFormat($order->order_date) }}
                            <br> Status: {{ $order->status }}
                            {{ $order->isCancelled() ? '('. \General::datetimeFormat($order->cancelled_at) .')' : null}}
                            @if ($order->isCancelled())
                            <br> Cancellation Note : {{ $order->cancellation_note}}
                            @endif
                            <br> Payment Status: {{ $order->payment_status }}
                            <br> Shipped by: {{ $order->shipping_service_name }}
                        </address>
                    </div>
                </div>
                <div class="table-content table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{!! \General::showAttributes($item->attributes) !!}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ \General::priceFormat($item->base_price) }}</td>
                                <td>{{ \General::priceFormat($item->sub_total) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">Order item not found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
