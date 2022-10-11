@extends('adminlte::page')

@section('title', 'Admin | Units Management')

@section('content_header')
    <h1 class="m-0 text-dark">Units</h1>
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
                <h3 class="card-title">Add Unit</h3>
            </div>

            {!! Form::open(array('route' => 'units.store','method'=>'POST')) !!}

            <div class="card-body">    
                <div class="form-group">
                <label for="input-name">Name</label>  
                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'id' => 'input-name')) !!}
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-role">Syllabus</label>                
                {!! Form::select('syllabus_id', ['' => 'Choose syllabus…'] + $syllabi, null, array('class' => 'form-control', 'id' => 'input-syllabus')) !!}
                @if ($errors->has('syllabus_id'))
                <span class="text-danger">{{ $errors->first('syllabus_id') }}</span>
                @endif
                </div>                              

                <div class="form-group">
                <label for="input-status">Status</label>
                {!! Form::select('status', array('0' => 'Disabled', '1' => 'Enabled'),'1', array('class' => 'form-control', 'id' => 'input-status')) !!}
                </div>
            </div>
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('units.index') }}" class="btn btn-danger">Cancel</a>    
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>                
@stop