@extends('adminlte::page')

@section('title', 'Admin | Units Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Units</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('units.index', $qString) }}">Back</a>            
        </div>
    </div>   
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Detail</h3>
            </div>
            <div class="card-body table-responsive">                                                   
                <div class="row">
                    <div class="col-sm-12">                                               
                        <table class="table ">                           
                            <tbody>                                 
                                <tr><td><b>Id</b></td><td>#{{ $unit->id }}</td></tr>
                                <tr><td><b>Name</b></td><td>{{ $unit->name }}</td></tr>
                                @if($unit->syllabus)
                                <tr><td><b>Syllabus</b></td><td>{{ $unit->syllabus->name }}</td></tr>
                                @endif                                
                                <tr><td><b>Date Added</b></td><td>{{ $unit->created_at }}</td></tr>
                                <tr><td><b>Date Modified</b></td><td>{{ $unit->updated_at }}</td></tr>
                                <tr><td><b>Status</b></td><td> @if($unit->status)
                                        <label class="badge badge-success">Active</label>
                                    @else
                                        <label class="badge badge-danger">Inactive</label>
                                    @endif</td></tr>                        
                            </tbody>
                        </table>                       
                    </div>
                </div>                                        
            </div>             
        </div>
    </div>
</div>                
@stop