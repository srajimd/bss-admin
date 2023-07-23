@extends('adminlte::page')

@section('title', 'Admin | Study Materials')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Study Materials</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('documents.index', $qString) }}">Back</a>            
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
                                <tr><td><b>Id</b></td><td>#{{ $document->id }}</td></tr>
                                <tr><td><b>Name</b></td><td>{{ $document->name }}</td></tr>
                                <tr><td><b>File Name</b></td><td><a href="{{ str_replace('public/','',url('/').Storage::url('app/')) . $document->file_path}}" target="_blank">{{ $document->file_name }}</a></td></tr>                    
                                <tr><td><b>Date Added</b></td><td>{{ $document->created_at }}</td></tr>
                                <tr><td><b>Date Modified</b></td><td>{{ $document->updated_at }}</td></tr>
                                <tr><td><b>Status</b></td><td> @if($document->status)
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