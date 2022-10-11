@extends('adminlte::page')

@section('title', 'Admin | Videos Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Videos</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('videos.index', $qString) }}">Back</a>            
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
                                <tr><td><b>Id</b></td><td>#{{ $video->id }}</td></tr>
                                <tr><td><b>Name</b></td><td>{{ $video->name }}</td></tr>
                                @if($video->course)
                                <tr><td><b>Course</b></td><td>{{ $video->course->name }}</td></tr>
                                @endif                                
                                <tr><td><b>Date Added</b></td><td>{{ $video->created_at }}</td></tr>
                                <tr><td><b>Date Modified</b></td><td>{{ $video->updated_at }}</td></tr>
                                <tr><td><b>Status</b></td><td> @if($video->status)
                                        <label class="badge badge-success">Active</label>
                                    @else
                                        <label class="badge badge-danger">Inactive</label>
                                    @endif</td></tr>                        
                            </tbody>
                        </table> 
                         <div class="col-sm-12 col-md-6">
                            <video width="600" height="450" controls>
                            <source src="{{ route('video.display', $video->file_identity) }}" type="video/mp4">
                            Your browser does not support the video tag.
                            </video>

                        </div>                      
                    </div>
                </div>                                        
            </div>             
        </div>
    </div>
</div>                
@stop