@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-5">
            @include('admin.attributes.option_form')
        </div>
        <div class="col-lg-7">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Options for : {{ $attribute->name }}</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 3rem">#</th>
                                <th>Name</th>
                                @role('Admin')
                                <th style="width: 7rem">Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attribute->attributeOptions as $option)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $option->name }}</td>
                                @role('Admin')
                                <td class="text-center">
                                    @can('edit_attributes')
                                    <a href="{{ url('admin/attributes/options/'. $option->id .'/edit') }}"
                                        class="btn btn-success btn-sm" title="edit">
                                        <span class="mdi mdi-square-edit-outline"></span>
                                    </a>
                                    @endcan

                                    @can('delete_attributes')
                                    {!! Form::open(['url' => 'admin/attributes/options/'. $option->id, 'class' =>
                                    'delete', 'style' => 'display:inline-block']) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button type="submit" class="btn btn-danger btn-sm" title="delete">
                                        <span class="mdi mdi-trash-can-outline"></span>
                                    </button>
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endrole
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No records found</td>
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
