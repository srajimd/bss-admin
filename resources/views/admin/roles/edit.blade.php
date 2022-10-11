@extends('adminlte::page')

@section('title', 'Admin | Roles Management')

@section('content_header')
    <h1 class="m-0 text-dark">Roles</h1>
    @if ($errors->any())
    <div class="alert alert-danger mt-2 alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-ban"></i> Whoops! Please check the form for errors carefully.    
    </div>
    @endif
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Role</h3>
            </div>

            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}

            <div class="card-body"> 
                <div class="form-group">
                <label for="input-name">Name</label>
                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'id' => 'input-name')) !!}
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                 <div class="form-group">
                <label for="input-permission">Permission</label>         
                <br/>
                @foreach($permission as $value)
                <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                {{ $value->name }}</label>
                <br/>
                @endforeach

                 @if ($errors->has('permission'))
                <span class="text-danger">{{ $errors->first('permission') }}</span>
                @endif
                </div>
            </div>
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('roles.index') }}" class="btn btn-danger">Cancel</a>    
            </div>
   
            {!! Form::close() !!}
        </div>
    </div>
</div>                
@stop