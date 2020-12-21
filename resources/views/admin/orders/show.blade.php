@extends('admin.layout')

@section('content')
<div class="content">
    <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5">
        <div class="d-flex justify-content-between">
            <h2 class="text-dark font-weight-medium">Order ID #{{ $order->code }}</h2>
            <div class="btn-group">
                <button class="btn btn-sm btn-secondary">
                    <i class="mdi mdi-content-save"></i> Save</button>
                <button class="btn btn-sm btn-secondary">
                    <i class="mdi mdi-printer"></i> Print</button>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-xl-4 col-lg-4">
                <p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">
                    Billing Address</p>
                <address>
                    {{ $order->customer_first_name }} {{ $order->customer_last_name }}
                    <br> {{ $order->customer_address1 }}
                    <br> {{ $order->customer_address2 }}
                    <br> Email: {{ $order->customer_email }}
                    <br> Phone: {{ $order->customer_phone }}
                    <br> Postcode: {{ $order->customer_postcode }}
                </address>
            </div>
            <div class="col-xl-4 col-lg-4">
                <p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">
                    Shipment Address</p>
                <address>
                    {{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
                    <br> {{ $order->shipment->address1 }}
                    <br> {{ $order->shipment->address2 }}
                    <br> Email: {{ $order->shipment->email }}
                    <br> Phone: {{ $order->shipment->phone }}
                    <br> Postcode: {{ $order->shipment->postcode }}
                </address>
            </div>
            <div class="col-xl-4 col-lg-4">
                <p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">
                    Details</p>
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
        <table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
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
        <div class="row justify-content-end">
            <div class="col-lg-5 col-xl-4 col-xl-3 ml-sm-auto">
                <ul class="list-unstyled mt-4">
                    <li class="mid pb-3 text-dark">Subtotal
                        <span
                            class="d-inline-block float-right text-default">{{ \General::priceFormat($order->base_total_price) }}</span>
                    </li>
                    <li class="mid pb-3 text-dark">Tax(10%)
                        <span
                            class="d-inline-block float-right text-default">{{ \General::priceFormat($order->tax_amount) }}</span>
                    </li>
                    <li class="mid pb-3 text-dark">Shipping Cost
                        <span
                            class="d-inline-block float-right text-default">{{ \General::priceFormat($order->shipping_cost) }}</span>
                    </li>
                    <li class="pb-3 text-dark">Total
                        <span class="d-inline-block float-right">{{ \General::priceFormat($order->grand_total) }}</span>
                    </li>
                </ul>
                @if (!$order->trashed())
                @if ($order->isPaid() && $order->isConfirmed())
                <a href="{{ url('admin/shipments/'. $order->shipment->id .'/edit')}}"
                    class="btn btn-block mt-2 btn-lg btn-primary btn-pill"> Procced to Shipment</a>
                @endif

                @if (in_array($order->status, [\App\Models\Order::CREATED, \App\Models\Order::CONFIRMED]))
                <a href="{{ url('admin/orders/'. $order->id .'/cancel')}}"
                    class="btn btn-block mt-2 btn-lg btn-warning btn-pill"> Cancel</a>
                @endif

                @if ($order->isDelivered())
                <a href="#" class="btn btn-block mt-2 btn-lg btn-success btn-pill" onclick="event.preventDefault();
						document.getElementById('complete-form-{{ $order->id }}').submit();"> Mark as Completed</a>

                {!! Form::open(['url' => 'admin/orders/complete/'. $order->id, 'id' => 'complete-form-'. $order->id,
                'style' => 'display:none']) !!}
                {!! Form::close() !!}
                @endif

                @if (!in_array($order->status, [\App\Models\Order::DELIVERED, \App\Models\Order::COMPLETED]))
                <a href="#" onclick="event.preventDefault();
											document.getElementById('delete-form-' + {{ $order->id }}).submit();"
                    class="btn btn-block mt-2 btn-lg btn-secondary btn-pill delete" order-id="{{ $order->id }}">
                    Remove</a>

                {!! Form::open(['url' => 'admin/orders/'. $order->id, 'class' => 'delete', 'id' => 'delete-form-'.
                $order->id, 'style' => 'display:none']) !!}
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::close() !!}
                @endif
                @else
                <a href="{{ url('admin/orders/restore/'. $order->id)}}"
                    class="btn btn-block mt-2 btn-lg btn-outline-secondary btn-pill restore"> Restore</a>
                <a href="#" class="btn btn-block mt-2 btn-lg btn-danger btn-pill delete" order-id="{{ $order->id }}">
                    Remove Permanently</a>

                {!! Form::open(['url' => 'admin/orders/'. $order->id, 'class' => 'delete', 'id' => 'delete-form-'.
                $order->id, 'style' => 'display:none']) !!}
                {!! Form::hidden('_method', 'DELETE') !!}
                {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
