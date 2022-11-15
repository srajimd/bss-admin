@extends('adminlte::page')

@section('title', 'Admin | Settings')
@section('content_header')
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
            <div class="card-header">
                <h3 class="card-title">Settings</h3>
            </div> 
            {!! Form::model($settings, ['method' => 'PATCH','route' => ['course.updatesettings']]) !!}
            <div class="card-body table-responsive">                                                   
                <div class="row">
                    <div class="col-sm-12">                   
                        @php
                            $id=!empty($settings->id)?$settings->id:''; 
                            $hard_copy_charge=!empty($settings->hard_copy_charge)?$settings->hard_copy_charge:''; 
                            $total_marks=!empty($settings->total_marks)?$settings->total_marks:'';
                            $created_at=!empty($settings->created_at)?$settings->created_at:'';
                            $updated_at=!empty($settings->updated_at)?$settings->updated_at:'';
                        @endphp   
                        {!! Form::hidden('id', null, array('id' => 'hidid')) !!}
                                                                        
                        <table class="table">                           
                            <tbody>   
                                <tr><td>Charge of Hardcopy</td><td>{!! Form::number('hard_copy_charge', null, array('min' => '0', 'placeholder' => 'Charge', 'class' => 'form-control', 'id' => 'hard_copy_charge')) !!}
                                @if ($errors->has('hard_copy_charge'))
                                <span class="text-danger">{{ $errors->first('hard_copy_charge') }}</span>
                                @endif
                                </td></tr>                              
                                <tr><td>Total Marks of Examination</td><td>{!! Form::number('total_marks', null, array('min' => '0', 'placeholder' => 'Total Marks', 'class' => 'form-control', 'id' => 'total_marks')) !!}
                                @if ($errors->has('total_marks'))
                                <span class="text-danger">{{ $errors->first('total_marks') }}</span>
                                @endif
                                </td></tr>                              
                                @if($created_at!='')
                                <tr><td><b>Created Date</b></td><td>{{ date('d-m-Y h:i:s A', strtotime($created_at)) }}</td></tr> 
                                @endif
                                @if($updated_at!='')
                                <tr><td><b>Updated Date</b></td><td>{{ date('d-m-Y h:i:s A', strtotime($updated_at)) }}</td></tr> 
                                @endif
                            </tbody>
                        </table>         
                    </div>                   
                </div>                                        
            </div> 
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Save</button>    
            </div> 
            {!! Form::close() !!}            
        </div>       
    </div>
</div> 
@stop               