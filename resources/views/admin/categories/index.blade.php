@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Categories</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials._flash', ['$errors' => $errors])
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 3rem">#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Parent</th>
                                @role('Admin')
                                <th style="width: 7rem">Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                            <tr>
                                <td class="text-center">
                                    {{ $categories->firstItem() - 1 + $loop->iteration }}
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->parent ? $category->parent->name : 'Yes' }}</td>
                                @role('Admin')
                                <td class="text-center">
                                    @can('edit_categories')
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-success btn-sm"
                                        title="edit">
                                        <span class="mdi mdi-square-edit-outline"></span>
                                    </a>
                                    @endcan

                                    @can('delete_categories')
                                    {!! Form::open(['route' => ['categories.destroy', $category], 'class' => 'delete
                                    d-inline-block']) !!}
                                    @method("DELETE")


                                    <button type="submit" class="btn btn-danger btn-sm" title="delete"><span
                                            class="mdi mdi-trash-can-outline"></span></button>

                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endrole
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No record found!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $categories->links() }}
                </div>
                @can('add_categories')
                <div class="card-footer text-right">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">+ Add New</a>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
