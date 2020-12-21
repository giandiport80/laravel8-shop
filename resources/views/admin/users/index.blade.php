@extends('admin.layout')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>Users</h2>
                </div>
                <div class="card-body">
                    @include('admin.partials.flash')
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 3rem">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                @role('Admin')
                                <th style="width: 7rem">Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <td class="text-center">{{ $users->firstItem() - 1 + $loop->iteration }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->implode('name', ', ') }}</td>
                                <td>{{ $user->created_at }}</td>
                                @role('Admin')
                                <td class="text-center">
                                    @if (!$user->hasRole('Admin'))
                                    @can('edit_categories')
                                    <a href="{{ url('admin/users/'. $user->id .'/edit') }}"
                                        class="btn btn-success btn-sm" title="edit">
                                        <span class="mdi mdi-square-edit-outline"></span>
                                    </a>
                                    @endcan

                                    @can('delete_categories')
                                    {!! Form::open(['url' => 'admin/users/'. $user->id, 'class' => 'delete', 'style' =>
                                    'display:inline-block']) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    <button type="submit" class="btn btn-danger btn-sm" title="delete">
                                        <span class="mdi mdi-trash-can-outline"></span>
                                    </button>
                                    {!! Form::close() !!}
                                    @endcan
                                    @endif
                                </td>
                                @endrole
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">No records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>

                @can('add_users')
                <div class="card-footer text-right">
                    <a href="{{ url('admin/users/create') }}" class="btn btn-primary btn-sm">+ Add New</a>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
