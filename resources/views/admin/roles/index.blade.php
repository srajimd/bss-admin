@extends('adminlte::page')

@section('title', 'Admin | Roles Management')

@section('content_header')                                 
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Roles</h1>
        </div>
        <div class="col-4 text-right">
            @can('role-create')
            <a class="btn btn-primary btn-sm" href="{{ route('roles.create') }}">Add Role</a>
            @endcan
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
                    <h3 class="card-title">Role List</h3>
                </div> -->
                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-sm-12">                                
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="border-top: none;">@sortablelink('id', 'No')</th>
                                        <th style="border-top: none;">@sortablelink('name', 'Name')</th>
                                        <th style="border-top: none;">@sortablelink('created_at', 'Date Added')</th>
                                        <th style="border-top: none;">Action</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @if( $roles->total() > 0 )
                                    @foreach ($roles as $key => $role)
                                    <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->created_at }}</td>                    
                                    <td>
                                        @can('role-edit')                                    
                                        <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fas fa-pen"></i></a>            
                                        @endcan
                                        @can('role-delete')    
                                        <form id="form-role" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')                                        
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirm('Are you sure ?') ? $('#form-role').submit() : false;"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endcan                    
                                    </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                    <td colspan="4">No records found. </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            @if($roles->total())
                            Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} entries
                            @endif
                        </div>
                        <div class="col-md-4 offset-md-4">
                             {!! $roles->appends(\Request::except('page'))->render() !!}
                        </div>
                    </div>                       
                </div>                
            </div>
        </div>  
    </div>
@stop


