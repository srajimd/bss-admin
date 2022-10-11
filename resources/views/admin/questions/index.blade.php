@extends('adminlte::page')

@section('title', 'Admin | Questions Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Questions</h1>
        </div>
        <div class="col-6 text-right">

            <a class="btn btn-secondary btn-sm" href="{{ route('imports.create') }}">Import</a>

            <a class="btn btn-primary btn-sm" href="{{ route('questions.create') }}">Add Question</a>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirm('Are you sure ?') ? $('#form-question').submit() : false;">Delete</button>
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
                    <form action="{{ route('questions.index') }}" method="GET" role="search">
                        <div class="row mb-3">
                            <div class="col-sm-3 offset-md-2">
                                <input type="text" name="filter[name]" value="{{ $name }}" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="filter[course_identifier]" value="{{ $course_identifier }}" class="form-control" placeholder="Course Id">
                            </div>
                            <div class="col-sm-2">
                                <select name="filter[status]" class="custom-select">
                                <option value="">Status</option>                                
                                <option value="1" @if($status == '1') selected @endif >Active</option>
                                <option value="0" @if($status == '0') selected @endif>InActive</option>
                                </select>  
                            </div>
                            <div class="col-sm-2">
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
                            <form action="{{ route('questions.deleteAll', $qString) }}" method="POST" id="form-question">
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
                                        <th>@sortablelink('id', 'Id')</th>
                                        <th>@sortablelink('name', 'Name')</th> 
                                         <th>@sortablelink('course_identifier', 'Course Id')</th>   
                                        <th>@sortablelink('status', 'Status')</th>
                                        <th>@sortablelink('created_at', 'Date Added')</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @if( $questions->total() > 0 )
                                    @foreach ($questions as $key => $question)
                                    <tr>
                                    <td>
                                        <div class="custom-checkbox">
                                        <input style="position: relative;" type="checkbox" class="custom-control-input" id="customCheckbox{{ $question->id }}" name="selected[]" value="{{ $question->id }}">
                                        <label for="customCheckbox{{ $question->id }}" class="custom-control-label"></label>
                                        </div>
                                    </td>    
                                    <td>#{{ $question->id }}</td>
                                    <td>{{ $question->name }}</td>
                                     <td>{{ optional($question->course)->course_identifier }}</td>
                                    <td>
                                    @if($question->status)
                                        <label class="badge badge-success">Active</label>
                                    @else
                                        <label class="badge badge-danger">Inactive</label>
                                    @endif                                                
                                    </td>                                    
                                    <td>{{ $question->created_at }}</td>
                                    <td>                                    
                                    @php 
                                    $qString = request()->query();
                                    if(count($qString) > 0){
                                        $params = array_merge( array('question' => $question->id), $qString);
                                    }else{
                                        $params = array('question' => $question->id);
                                    } 
                                    @endphp
                                    <a class="btn btn-primary btn-sm" href="{{ route('questions.edit', $params) }}"><i class="fas fa-pen"></i></a>
                                    <a class="btn btn-info btn-sm" href="{{ route('questions.show', $params) }}"><i class="fas fa-eye"></i></a>
                                    </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                    <td colspan="7">No records found. </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            </form>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-4">
                            @if($questions->total())
                            Showing {{ $questions->firstItem() }} to {{ $questions->lastItem() }} of {{ $questions->total() }} entries
                            @endif
                        </div>
                        <div class="col-md-4 offset-md-4">
                            {!! $questions->appends(\Request::except('page'))->render() !!}
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

