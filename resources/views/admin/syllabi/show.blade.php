@extends('adminlte::page')

@section('title', 'Admin | Syllabuses Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Syllabuses</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('syllabi.index', $qString) }}">Back</a>            
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
                                <tr><td><b>Id</b></td><td>#{{ $syllabus->id }}</td></tr>
                                <tr><td><b>Name</b></td><td>{{ $syllabus->name }}</td></tr>

                                <tr><td><b>Topic</b></td><td>{{ optional($syllabus->topic)->name }}</td></tr>

                                
                                <tr><td><b>Course</b></td><td>{{ optional($syllabus->course)->name }}</td></tr>
                                                            
                                <tr><td><b>Date Added</b></td><td>{{ $syllabus->created_at }}</td></tr>
                                <tr><td><b>Date Modified</b></td><td>{{ $syllabus->updated_at }}</td></tr>
                                <tr><td><b>Status</b></td><td> @if($syllabus->status)
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
        
        @if(count($lessons) > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lessons</h3>
            </div>
            <div class="card-body table-responsive">                                                   
                <div class="row">
                    <div class="col-sm-12">                                               
                        <table class="table ">
                            <thead>
                            <tr>                                        
                            <th>No.</th>
                            <th>Title</th>                                         
                            </tr>
                            </thead>                           
                            <tbody>
                            @php
                                $i = 1;
                            @endphp 
                            @foreach($lessons as $lesson)                                
                            <tr>                                                                      
                            <td>{{ $i }}</td>
                            <td>{{ $lesson->name }}</td>
                            </tr>
                            @php
                                $i++;
                            @endphp                       
                            @endforeach
                            </tbody>
                        </table>                       
                    </div>
                </div>                                        
            </div>             
        </div>
        @endif
    </div>
</div>                
@stop