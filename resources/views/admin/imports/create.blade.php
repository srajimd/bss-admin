@extends('adminlte::page')

@section('title', 'Admin | Question & Answers Import')

@section('content_header')
    <h1 class="m-0 text-dark">Question & Answers Import</h1>
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
                <h3 class="card-title">Import</h3>
            </div>

            {!! Form::open(array('route' => 'imports.store', 'method'=>'POST', 'enctype' => 'multipart/form-data', 'files' => true)) !!}

            <div class="card-body">    
                              

                <div class="form-group">
                <label for="input-file">EXCEL</label>  
                <input type="file" name="file" class="form-control" id="input-file">
                @if ($errors->has('file'))
                <span class="text-danger">{{ $errors->first('file') }}</span>
                @endif
                </div>         

            </div>
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('questions.index') }}" class="btn btn-danger">Cancel</a>    
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>                
@stop