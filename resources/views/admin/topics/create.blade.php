@extends('adminlte::page')

@section('title', 'Admin | Topics Management')

@section('content_header')
    <h1 class="m-0 text-dark">Topics</h1>
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
                <h3 class="card-title">Add Topic</h3>
            </div>

            {!! Form::open(array('route' => 'topics.store','method'=>'POST')) !!}

            <div class="card-body">    
                <div class="form-group">
                <label for="input-name">Name</label>  
                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'id' => 'input-name')) !!}
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-meta-title">Meta Title</label>    
                {!! Form::text('meta_title', null, array('placeholder' => 'Meta Title', 'class' => 'form-control', 'id' => 'input-meta-title')) !!}               
                </div>
               
                 <div class="form-group">
                <label for="input-meta-description">Meta Description</label>    
                {!! Form::textarea('meta_description', null, array('placeholder' => 'Meta Description', 'class' => 'form-control', 'id' => 'input-meta-description', 'rows' => 3, 'cols' => 3)) !!}               
                </div>

                <div class="form-group">
                <label for="input-status">Status</label>
                {!! Form::select('status', array('0' => 'Disabled', '1' => 'Enabled'),'1', array('class' => 'form-control', 'id' => 'input-status')) !!}
                </div>
            </div>
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('topics.index') }}" class="btn btn-danger">Cancel</a>    
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>                
@stop