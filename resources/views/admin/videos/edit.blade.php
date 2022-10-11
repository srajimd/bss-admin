@extends('adminlte::page')

@section('title', 'Admin | Videos Management')

@section('content_header')
    <h1 class="m-0 text-dark">Videos</h1>
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
                <h3 class="card-title">Edit Video</h3>
            </div>
            @php
            $qString = request()->query();
            if(count($qString) > 0){
                $params = array_merge( array('video' => $video->id), $qString);
            }else{
                $params = array('video' => $video->id);
            } 
            @endphp

            {!! Form::model($video, ['method' => 'PATCH', 'route' => ['videos.update', $params], 'enctype' => 'multipart/form-data', 'files' => true]) !!}

            <div class="card-body">    
                <div class="form-group">
                <label for="input-name">Name</label>      
                {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'id' => 'input-name')) !!}
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                </div>

               <div class="form-group">
                <label for="input-topic">Topic</label>                
                {!! Form::select('topic_id', ['' => 'Choose topic...'] + $topics, null, array('class' => 'form-control', 'id' => 'input-topic')) !!}
                @if ($errors->has('topic_id'))
                <span class="text-danger">{{ $errors->first('topic_id') }}</span>
                @endif
                </div> 

                <div class="form-group">
                <label for="input-course">Course</label> 
                <select name="course_id" class="form-control" id="input-course">
                </select>
                 @if ($errors->has('course_id'))
                <span class="text-danger">{{ $errors->first('course_id') }}</span>
                @endif
                </div> 

                <div class="form-group">
                <label for="input-file">Video</label>  
                <input type="file" name="file" class="form-control" id="input-file">
                <span class="text-danger">Filename : {{ $video->file_name }}</span>                
                @if ($errors->has('file'))
                <span class="text-danger">{{ $errors->first('file') }}</span>
                @endif
                </div>                                 

                <div class="form-group">
                <label for="input-status">Status</label>
                {!! Form::select('status', array('0' => 'Disabled', '1' => 'Enabled'), $video->status, array('class' => 'form-control', 'id' => 'input-status')) !!}
                </div>
            </div>
            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('videos.index', $qString) }}" class="btn btn-danger">Cancel</a>    
            </div>       

            {!! Form::close() !!}

            </div>
    </div>
</div>                
@stop

@section('css')
<style type="text/css">
    input[type='file'] {        
        color:transparent;    
    }
</style>
@stop

@section('js')
<script type="text/javascript">
    $(document).on('change', '#input-file', function() { 
        $(this).css('color','#495057');
    }); 

    $('select[name=\'topic_id\']').on('change', function() {
    let topic_id = this.value;
    
    $.ajax({
        url: '{{ route('topic.courses') }}',
        dataType: 'json',
        data: { topic_id : topic_id },
        beforeSend: function() {
            $('select[name=\'topic_id\']').after(' <i class="fas fa-circle-notch fa-spin"></i>');
        },
        complete: function() {
            $('.fa-spin').remove();
        },
        success: function(json) {            

            var course_id = '{{ $video->course_id }}';

            html = '<option value="">Choose course...</option>';

            if (json['courses'] && json['courses'] != '') {
                for (i = 0; i < json['courses'].length; i++) {
                    html += '<option value="' + json['courses'][i]['id'] + '"';

                    if (json['courses'][i]['id'] == course_id) {
                        html += ' selected="selected"';
                    }

                    html += '>' + json['courses'][i]['name'] + '</option>';
                }
            } 

            $('select[name=\'course_id\']').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
    
});

$('select[name=\'topic_id\']').trigger('change');       
</script>
@stop