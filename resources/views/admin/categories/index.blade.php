@extends('admin.layout')

@section('content')
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
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td class="text-center" style="width: 3rem">{{ $categories->firstItem() - 1 + $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->parent_id }}</td>
                            <td class="text-center" style="width: 7rem">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-success btn-sm"
                                    title="edit category">
                                    <span class="mdi mdi-square-edit-outline"></span>
                                </a>

                                {!! Form::open(['route' => ['categories.destroy', $category], 'class' => 'delete d-inline-block']) !!}
                                @method("DELETE")


                                <button type="submit" class="btn btn-danger btn-sm" title="delete category"><span
                                        class="mdi mdi-trash-can-outline"></span></button>

                                {!! Form::close() !!}
                            </td>
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
            <div class="card-footer text-right">
                <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">+ Add New</a>
            </div>
        </div>
    </div>
</div>
@endsection
