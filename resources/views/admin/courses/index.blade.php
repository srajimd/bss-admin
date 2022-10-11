@extends('adminlte::page')

@section('title', 'Admin | Courses Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Courses</h1>
        </div>
        <div class="col-6 text-right">
            <a class="btn btn-primary btn-sm" href="{{ route('courses.create') }}">Add Course</a>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirm('Are you sure ?') ? $('#form-course').submit() : false;">Delete</button>
        </div>
    </div>
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
                <!-- <div class="card-header">
                    <h3 class="card-title">User List</h3>
                </div> -->
                <div class="card-body table-responsive">                                               
                    <form action="{{ route('courses.index') }}" method="GET" role="search">
                        <div class="row mb-3">
                            <div class="col-sm-3 ">
                                <input type="text" name="filter[name]" value="{{ $name }}" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="filter[course_identifier]" value="{{ $course_identifier }}" class="form-control" placeholder="Course Id">
                            </div>
                            <div class="col-sm-3">
                                <select name="filter[status]" class="custom-select">
                                <option value="">Status</option>                                
                                <option value="1" @if($status == '1') selected @endif >Active</option>
                                <option value="0" @if($status == '0') selected @endif>InActive</option>
                                </select>  
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group date" id="start-date">
                                    <input type="text" name="filter[created_at]" value="{{ $created_at }}" class="form-control" placeholder="Date Added">
                                    <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">                                    
                                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>                    
                    <div class="row">
                        <div class="col-sm-12">
                            @php 
                            $qString = request()->query();                            
                            @endphp
                            <form action="{{ route('courses.deleteAll', $qString) }}" method="POST" id="form-course">
                            @csrf                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 1px;">                                            
                                            <div class="custom-checkbox">
                                            <input style="position: relative;" type="checkbox" class="custom-control-input" id="mainCheckbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);">
                                            <label for="mainCheckbox" class="custom-control-label"></label>
                                            </div>
                                        </th>
                                        <th>@sortablelink('course_identifier', 'Id')</th>
                                        <th>@sortablelink('name', 'Name')</th>
                                        <th>@sortablelink('duration', 'Duration')</th>
                                        <th>@sortablelink('amount', 'Amount')</th>
                                        <th>@sortablelink('status', 'Status')</th>
                                        <th>@sortablelink('created_at', 'Date Added')</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @if( $courses->total() > 0 )
                                    @foreach ($courses as $key => $course)
                                    <tr>
                                    <td>
                                        <div class="custom-checkbox">
                                        <input style="position: relative;" type="checkbox" class="custom-control-input" id="customCheckbox{{ $course->id }}" name="selected[]" value="{{ $course->id }}">
                                        <label for="customCheckbox{{ $course->id }}" class="custom-control-label"></label>
                                        </div>
                                    </td>    
                                    <td>#{{ $course->course_identifier }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->duration }}</td>
                                    <td>{{ $course->amount }}</td>
                                    <td>
                                    @if($course->status)
                                        <label class="badge badge-success">Active</label>
                                    @else
                                        <label class="badge badge-danger">Inactive</label>
                                    @endif                                                
                                    </td>                                    
                                    <td>{{ $course->created_at }}</td>
                                    <td>                                    
                                    @php 
                                    $qString = request()->query();
                                    if(count($qString) > 0){
                                        $params = array_merge( array('course' => $course->id), $qString);
                                    }else{
                                        $params = array('course' => $course->id);
                                    } 
                                    @endphp
                                    <a class="btn btn-primary btn-sm" href="{{ route('courses.edit', $params) }}"><i class="fas fa-pen"></i></a>
                                    <a class="btn btn-info btn-sm" href="{{ route('courses.show', $params) }}"><i class="fas fa-eye"></i></a>
                                    </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                    <td colspan="8">No records found. </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            </form>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-4">
                            @if($courses->total())
                            Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} entries
                            @endif
                        </div>
                        <div class="col-md-4 offset-md-4">
                            {!! $courses->appends(\Request::except('page'))->render() !!}
                        </div>
                    </div>
                </div>                
            </div>
        </div>  
    </div>
@stop

@section('css')    
    <link rel="stylesheet" href="/vendor/dg-plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
@stop

@section('js')
    <script src="/vendor/dg-plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript">
     //Date range picker
    $('#start-date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });
    </script>
@stop

