@extends('admin.layout')
@php
$formTitle = !empty($category) ? 'Update' : 'New'
@endphp

@section('content')
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
                <div class="form-footer pt-3 border-top">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
