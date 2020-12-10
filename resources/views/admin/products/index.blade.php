@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Products</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials._flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 3rem">#</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th style="width: 7rem">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td class="text-center">
                                    {{ $products->firstItem() - 1 + $loop->iteration }}
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price) }}</td>
                                <td>{{ $product->statusLabel() }}</td>
                                <td class="text-center">
                                    <a href="{{ url('admin/products/'. $product->id .'/edit') }}"
                                        class="btn btn-success btn-sm" title="edit">
                                        <span class="mdi mdi-square-edit-outline"></span>
                                    </a>

                                    @can('delete_products')
                                    {!! Form::open(['url' => 'admin/products/'. $product->id, 'class' => 'delete
                                    d-inline-block']) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}

                                    <button type="submit" class="btn btn-danger btn-sm" title="delete">
                                        <span class="mdi mdi-trash-can-outline"></span>
                                    </button>
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No records found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $products->links() }}
                </div>
                @can('add_products')
                <div class="card-footer text-right">
                    <a href="{{ url('admin/products/create') }}" class="btn btn-primary btn-sm">+ Add New</a>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
