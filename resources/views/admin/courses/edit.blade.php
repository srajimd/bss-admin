@extends('adminlte::page')

@section('title', 'Admin | Courses Management')

@section('content_header')
    <h1 class="m-0 text-dark">Courses</h1>
    @if ($errors->any())
    <div class="alert alert-danger mt-2 alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="icon fas fa-ban"></i> Whoops! Please check the form for errors carefully.    
    </div>
    @endif
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Course</h3>
            </div>
            @php
            $qString = request()->query();
            if(count($qString) > 0){
                $params = array_merge( array('course' => $course->id), $qString);
            }else{
                $params = array('course' => $course->id);
            } 
            @endphp

            {!! Form::model($course, ['method' => 'PATCH','route' => ['courses.update', $params]]) !!}

            <div class="card-body">    
                <div class="form-group">
                <label for="input-name">Name</label>      
                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'id' => 'input-name')) !!}
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

                <div class="form-group">
                <label for="input-name">Course Id</label>      
                {!! Form::text('course_identifier', null, array('placeholder' => 'Course Id', 'class' => 'form-control', 'id' => 'input-course-identifier')) !!}               
                </div>

                <div class="form-group">
                <label for="input-role">Topic</label>                
                {!! Form::select('topic_id', ['' => 'Choose topic…'] + $topics, null, array('class' => 'form-control', 'id' => 'input-topic')) !!}
                </div>

                <div class="form-group">
                <label for="input-name">Duration <small>(It should be in days eg. 60)</small></label>  
                {!! Form::number('duration', null, array('min' => '0', 'placeholder' => 'Duration', 'class' => 'form-control', 'id' => 'input-duration')) !!}
                @if ($errors->has('duration'))
                <span class="text-danger">{{ $errors->first('duration') }}</span>
                @endif                
                </div>

                <div class="form-group">
                <label for="input-name">Amount <small>(350.00)</small></label>  
                {!! Form::text('amount', null, array('placeholder' => 'Amount', 'class' => 'form-control', 'id' => 'input-amount')) !!}  
                @if ($errors->has('amount'))
                <span class="text-danger">{{ $errors->first('amount') }}</span>
                @endif              
                </div>

                 <div class="form-group">
                <label for="input-status">Certification</label>
                {!! Form::select('certification', array('0' => 'No', '1' => 'Yes'), $course->certification, array('class' => 'form-control', 'id' => 'input-certification')) !!}
                </div>

                <div class="form-group">
                <label for="input-other-information">Other Information</label>    
                {!! Form::textarea('other_information', null, array('placeholder' => 'Other Information', 'class' => 'form-control', 'id' => 'input-other-information', 'rows' => 3, 'cols' => 3)) !!}               
                </div>

                <div class="form-group">
                <label for="input-meta-title">Meta Title</label>    
                {!! Form::text('meta_title', null, array('placeholder' => 'Meta Title', 'class' => 'form-control', 'id' => 'input-meta-title')) !!}               
                </div>
               
                 <div class="form-group">
                <label for="input-meta-description">Meta Description</label>    
                {!! Form::textarea('meta_description', null, array('placeholder' => 'Meta Description', 'class' => 'form-control', 'id' => 'input-meta-description', 'rows' => 3, 'cols' => 3)) !!}               
                </div>

                <div class="form-group">
                <label for="input-status">Status</label>
                {!! Form::select('status', array('0' => 'Disabled', '1' => 'Enabled'), $course->status, array('class' => 'form-control', 'id' => 'input-status')) !!}
                </div>
            </div>
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('courses.index', $qString) }}" class="btn btn-danger">Cancel</a>    
            </div>       

            {!! Form::close() !!}

            </div>
    </div>
</div>                
@stop