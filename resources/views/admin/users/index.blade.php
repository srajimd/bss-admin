@extends('adminlte::page')

@section('title', 'Admin | Users Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Users</h1>
        </div>
        <div class="col-6 text-right">
            <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}">Add User</a>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirm('Are you sure ?') ? $('#form-user').submit() : false;">Delete</button>
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
                    <div class="row mb-3">                        
                        <div class="col-md-4 offset-md-8">
                            <form action="{{ route('users.index') }}" method="GET" role="search">
                                <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ $search }}">
                                <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="{{ route('users.deleteAll') }}" method="POST" id="form-user">
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
                                        <th>@sortablelink('email', 'Email')</th>
                                        <th>@sortablelink('mobile_number', 'Mobile Number')</th>
                                        <th>@sortablelink('status', 'Status')</th>
                                        <th>@sortablelink('created_at', 'Date Added')</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @if( $users->total() > 0 )
                                    @foreach ($users as $key => $user)
                                    <tr>
                                    <td>
                                        <div class="custom-checkbox">
                                        <input style="position: relative;" type="checkbox" class="custom-control-input" id="customCheckbox{{ $user->id }}" name="selected[]" value="{{ $user->id }}">
                                        <label for="customCheckbox{{ $user->id }}" class="custom-control-label"></label>
                                        </div>
                                    </td>    
                                    <td>#{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile_number }}</td>
                                    <td>
                                    @if($user->status)
                                        <label class="badge badge-success">Active</label>
                                    @else
                                        <label class="badge badge-danger">Inactive</label>
                                    @endif                                                
                                    </td> 
                                    <td>{{ $user->created_at }}</td>
                                    <td>                                    
                                    <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fas fa-pen"></i></a>
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
                            @if($users->total())
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                            @endif
                        </div>
                        <div class="col-md-4 offset-md-4">
                            {!! $users->appends(\Request::except('page'))->render() !!}
                        </div>
                    </div>
                </div>                
            </div>
        </div>  
    </div>
@stop

