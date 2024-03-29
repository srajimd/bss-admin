@extends('adminlte::page')

@section('title', 'Admin | Questions Management')

@section('content_header')
    <h1 class="m-0 text-dark">Questions</h1>
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
                <h3 class="card-title">Add Question</h3>
            </div>

            {!! Form::open(array('route' => 'questions.store','method'=>'POST')) !!}

            <div class="card-body">    
                <div class="form-group">
                <label for="input-name">Name</label>  
                {!! Form::textarea('name', null, array('placeholder' => 'Name', 'class' => 'form-control', 'id' => 'input-name')) !!}
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
                <label for="input-status">Status</label>
                {!! Form::select('status', array('0' => 'Disabled', '1' => 'Enabled'),'1', array('class' => 'form-control', 'id' => 'input-status')) !!}
                </div>
            </div>
            
            <div class="card-body">
                <div class="tab-pane" id="tab-answer">
                  <div class="table-responsive">
                    <table id="answer" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <td class="text-left"><h3 class="card-title">Add Answers</h3></td>                                           
                          <td></td>
                        </tr>
                      </thead>
                      <tbody>

                        @php
                            $answer_row = 0;
                        @endphp
                        @foreach(old('answer', []) as $key => $value)                        
                        <tr id="answer-row{{ $answer_row }}" class="answer-row">                                        
                        <td class="text-right">
                            @php /*
                            <input type="text" 
                            name="answer[{{ $answer_row }}][name]" 
                            value="{{ old('answer.'.$key.'.name') }}" 
                            placeholder="Answer" 
                            class="form-control" />
                            */
                            @endphp 

                             <textarea name="answer[{{ $answer_row }}][name]" placeholder="Answer" 
                            class="form-control">{{ old('answer.'.$key.'.name') }}</textarea>

                            @if ($errors->has('answer.'.$key.'.name'))
                            <span class="text-danger">{{ $errors->first('answer.'.$key.'.name') }}</span>
                            @endif
                        </td>

                        <td class="text-left">
                            <button type="button" 
                            onclick="$('#answer-row{{ $answer_row }}').remove();if($('.answer-row').length < 4) $('#addanswer').attr('disabled', false);" 
                            data-toggle="tooltip" 
                            title="Remove" 
                            class="btn btn-danger">
                            <i class="fa fa-minus-circle"></i></button>
                        </td>
                        </tr>                                            
                        @php
                            $answer_row++;
                        @endphp                                          
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                        <td colspan="1"></td>
                        <td class="text-left">
                            <button type="button" 
                            onclick="addAnswer()" 
                            data-toggle="tooltip" 
                            title="Add Answer" 
                            class="btn btn-primary" id="addanswer">
                            <i class="fa fa-plus-circle"></i></button>
                        </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
            </div>

            <div class="card-footer">    
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('questions.index') }}" class="btn btn-danger">Cancel</a>    
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>                
@stop

@section('js')
<script type="text/javascript">
var answer_row = '{{ $answer_row }}';

function addAnswer() {
    html  = '<tr id="answer-row' + answer_row + '" class="answer-row">';
   
    //html += '  <td class="text-right"><input type="text" name="answer[' + answer_row + '][name]" value="" placeholder="Answer" class="form-control" /></td>';

    html += '  <td class="text-right"><textarea name="answer[' + answer_row + '][name]" placeholder="Answer" class="form-control" /></textarea></td>';
   
    html += '  <td class="text-left"><button type="button" onclick="$(\'#answer-row' + answer_row + '\').remove();if($(\'.answer-row\').length < 4) $(\'#addanswer\').attr(\'disabled\', false);" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#answer tbody').append(html);
    
    if($('.answer-row').length >= 4){
        $('#addanswer').attr('disabled', true);
    }else{
        $('#addanswer').attr('disabled', false);
    }

    answer_row++;
}


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

            var course_id = '{{ old('course_id') }}';

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