@extends('adminlte::page')

@section('title', 'Admin | Dashboard')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
    @if ($message = Session::get('success'))
    <div class="alert alert-success mt-2 alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-check"></i> {{ $message }}
    </div>       
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">You are logged in!</p>
                                            

                </div>
            </div>
        </div>
    </div>
@stop
