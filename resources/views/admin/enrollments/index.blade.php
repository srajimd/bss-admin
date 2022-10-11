@extends('adminlte::page')

@section('title', 'Admin | Enrollments Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Enrollments</h1>
        </div>
        <!-- <div class="col-6 text-right">
            <a class="btn btn-primary btn-sm" href="{{ route('courses.create') }}">Add Course</a>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirm('Are you sure ?') ? $('#form-course').submit() : false;">Delete</button>
        </div> -->
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
                    <!-- <form action="{{ route('courses.index') }}" method="GET" role="search">
                        <div class="row mb-3">
                            <div class="col-sm-3 offset-md-2">
                                <input type="text" name="filter[name]" value="{{ $name }}" class="form-control" placeholder="Name">
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
                    </form>   -->                  
                    <div class="row">
                        <div class="col-sm-12">
                            @php 
                            $qString = request()->query();                            
                            @endphp
                            <form id="form-enrollment">
                            @csrf                        
                            <table class="table">
                                <thead>
                                    <tr>                                        
                                        <th>No</th>
                                        <th>@sortablelink('student', 'Student')</th>
                                        <th>@sortablelink('name', 'Name')</th>
                                        <th>@sortablelink('name', 'email')</th>
                                        <th>@sortablelink('duration', 'Duration')</th>
                                        <th>@sortablelink('amount', 'Amount')</th>
                                        <th>@sortablelink('status', 'Status')</th>
                                        <th>@sortablelink('created_at', 'Date Added')</th>
                                        <th>@sortablelink('created_at', 'Expiry Date')</th>
                                        
                                    </tr>
                                </thead>
                                <tbody> 
                                    @if( $enrollments->total() > 0 )
                                    @foreach ($enrollments as $key => $enrollment)
                                    <tr>                                    
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $enrollment->customer }}</td>
                                    <td>{{ $enrollment->name }}</td>
                                    <td>{{ $enrollment->email }}</td>
                                    <td>{{ $enrollment->duration }}</td>
                                    <td>{{ $enrollment->amount }}</td>
                                    <td>
                                    @if($enrollment->status)
                                        <label class="badge badge-success">Success</label>
                                    @else
                                        <label class="badge badge-danger">Pending</label>
                                    @endif                                                
                                    </td>                                    
                                    <td>{{ $enrollment->created_at }}</td>
                                    <td>{{ $enrollment->expiry_date }}</td>
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
                            @if($enrollments->total())
                            Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }} of {{ $enrollments->total() }} entries
                            @endif
                        </div>
                        <div class="col-md-4 offset-md-4">
                            {!! $enrollments->appends(\Request::except('page'))->render() !!}
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

