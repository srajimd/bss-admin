@extends('adminlte::page')

@section('title', 'Admin | Topics Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Topics</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('topics.index', $qString) }}">Back</a>            
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
                                <tr><td><b>Id</b></td><td>#{{ $topic->id }}</td></tr>
                                <tr><td><b>Name</b></td><td>{{ $topic->name }}</td></tr>
                                <tr><td><b>Meta Title</b></td><td>{{ $topic->meta_title }}</td></tr>
                                <tr><td><b>Meta Description</b></td><td>{{ $topic->meta_description }}</td></tr>
                                <tr><td><b>Date Added</b></td><td>{{ $topic->created_at }}</td></tr>
                                <tr><td><b>Date Modified</b></td><td>{{ $topic->updated_at }}</td></tr>
                                <tr><td><b>Status</b></td><td> @if($topic->status)
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