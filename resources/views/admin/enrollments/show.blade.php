@extends('adminlte::page')

@section('title', 'Admin | Courses Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Courses</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('courses.index', $qString) }}">Back</a>            
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
                                <tr><td><b>Id</b></td><td>#{{ $course->id }}</td></tr>
                                <tr><td><b>Name</b></td><td>{{ $course->name }}</td></tr>
                                @if($course->topic)
                                <tr><td><b>Topic</b></td><td>{{ $course->topic->name }}</td></tr>
                                @endif
                                <tr><td><b>Duration</b></td><td>{{ $course->duration }}</td></tr>
                                <tr><td><b>Amount</b></td><td>{{ $course->amount }}</td></tr>
                                <tr><td><b>Certification</b></td><td>@if($course->certification)
                                    Yes
                                    @else
                                    No
                                    @endif</td></tr>
                                <tr><td><b>Other Information</b></td><td>{{ $course->other_information }}</td></tr>
                                <tr><td><b>Meta Title</b></td><td>{{ $course->meta_title }}</td></tr>
                                <tr><td><b>Meta Description</b></td><td>{{ $course->meta_description }}</td></tr>
                                <tr><td><b>Date Added</b></td><td>{{ $course->created_at }}</td></tr>
                                <tr><td><b>Date Modified</b></td><td>{{ $course->updated_at }}</td></tr>
                                <tr><td><b>Status</b></td><td> @if($course->status)
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