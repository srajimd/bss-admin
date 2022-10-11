@extends('adminlte::page')

@section('title', 'Admin | Admins Management')

@section('content_header')
    <h1 class="m-0 text-dark">Admins</h1>
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
                <h3 class="card-title">Add Admin</h3>
            </div>

            {!! Form::open(array('route' => 'admins.store','method'=>'POST')) !!}

            <div class="card-body">    
                <div class="form-group">
                <label for="input-name">Name</label>  
                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'id' => 'input-name')) !!}
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-email">Email</label>    
                {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control', 'id' => 'input-email')) !!}
                @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-password">Password</label>
                {!! Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control', 'id' => 'input-password')) !!}
                @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-confirm-password">Confirm Password</label>        
                {!! Form::password('confirm_password', array('placeholder' => 'Confirm Password', 'class' => 'form-control', 'id' => 'input-confirm-password')) !!}
                 @if ($errors->has('confirm_password'))
                <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-role">Role</label>
                {!! Form::select('roles[]', $roles,[], array('class' => 'form-control', 'multiple', 'id' => 'input-role')) !!}
                @if ($errors->has('roles'))
                <span class="text-danger">{{ $errors->first('roles') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-status">Status</label>
                {!! Form::select('status', array('0' => 'Disabled', '1' => 'Enabled'),'1', array('class' => 'form-control', 'id' => 'input-status')) !!}
                </div>
            </div>
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admins.index') }}" class="btn btn-danger">Cancel</a>    
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>                
@stop