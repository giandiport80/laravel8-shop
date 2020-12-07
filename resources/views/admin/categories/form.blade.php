@extends('admin.layout')
@php
$formTitle = !empty($category) ? 'Update' : 'New'
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>{{ $formTitle }} Category</h2>
                </div>
                <div class="card-body">

                    @include('admin.partials._flash', ['$errors' => $errors])

                    @if(!empty($category))
                    {!! Form::model($category, ['route' => ['categories.update', $category->id], 'method' => 'PUT']) !!}
                    {!! Form::hidden('id') !!}
                    @else
                    {!! Form::open(['route' => 'categories.store']) !!}
                    @endif
                    <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'category name...']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('parent_id', 'Parent') !!}
                        {!! General::selectMultilevel('parent_id', $categories, ['class' => 'form-control', 'selected' =>
                        !empty(old('parent_id')) ? old('parent_id') : (!empty($category['parent_id']) ?
                        $category['parent_id'] : ''), 'placeholder' => '-- Choose Category --']) !!}
                    </div>
                    <div class="form-footer pt-3 border-top">
                        <a href="{{ route('categories.index') }}" class="btn btn-primary btn-danger">Back</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
