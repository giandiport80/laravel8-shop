@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Attributes</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 3rem">#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Type</th>

                                <th style="width: 10rem">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attributes as $attribute)
                            <tr>
                                <td class="text-center">{{ $attributes->firstItem() - 1 + $loop->iteration }}</td>
                                <td>{{ $attribute->code }}</td>
                                <td>{{ $attribute->name }}</td>
                                <td>{{ $attribute->type }}</td>
                                <td class="text-center">
                                    @can('edit_attributes')
                                    <a href="{{ url('admin/attributes/'. $attribute->id .'/edit') }}"
                                        class="btn btn-success btn-sm" title="edit">
                                        <span class="mdi mdi-square-edit-outline"></span>
                                    </a>
                                    @endcan

                                    @can('add_attributes')
                                    @if ($attribute->type == 'select')
                                    <a href="{{ url('admin/attributes/'. $attribute->id .'/options') }}"
                                        class="btn btn-info btn-sm" title="option">
                                        <span class="mdi mdi-settings"></span>
                                    </a>
                                    @endif
                                    @endcan

                                    @can('delete_attributes')
                                    {!! Form::open(['url' => 'admin/attributes/'. $attribute->id, 'class' => 'delete',
                                    'style' => 'display:inline-block']) !!}
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
                                <td colspan="5" class="text-center">No records found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $attributes->links() }}
                </div>
                @can('add_attributes')
                <div class="card-footer text-right">
                    <a href="{{ url('admin/attributes/create') }}" class="btn btn-primary btn-sm">+ Add New</a>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
