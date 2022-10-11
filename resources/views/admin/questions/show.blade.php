@extends('adminlte::page')

@section('title', 'Admin | Questions Management')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">Questions</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('questions.index', $qString) }}">Back</a>            
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
                                <tr><td><b>Id</b></td><td>#{{ $question->id }}</td></tr>
                                <tr><td><b>Name</b></td><td>{{ $question->name }}</td></tr>
                                @if($question->course)
                                <tr><td><b>Course</b></td><td>{{ $question->course->name }}</td></tr>
                                @endif                                
                                <tr><td><b>Date Added</b></td><td>{{ $question->created_at }}</td></tr>
                                <tr><td><b>Date Modified</b></td><td>{{ $question->updated_at }}</td></tr>
                                <tr><td><b>Status</b></td><td> @if($question->status)
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
        
        @if(count($answers) > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Answers</h3>
            </div>
            <div class="card-body table-responsive">                                                   
                <div class="row">
                    <div class="col-sm-12">                                               
                        <table class="table ">
                            <thead>
                            <tr>                                        
                            <th>No.</th>
                            <th>Answer</th> 
                            <th>Correct Answer</th>  
                            <th></th>                                   
                            </tr>
                            </thead>                           
                            <tbody>
                            @php
                                $i = 1;
                            @endphp 
                            @foreach($answers as $answer)                                
                            <tr>                                                                      
                            <td>{{ $i }}</td>
                            <td>{{ $answer->name }}</td>
                           
                            <td>
                                @if($answer->correct_answer)
                                    <i class="fas fa-check-circle text-success"> True</i>
                                    @else
                                    <i class="fas fa-minus text-danger"></i> 
                                 @endif
                            </td>

                            <td><button quesId='{{ $answer->question_id }}' ansId='{{ $answer->id }}' class="btn btn-warning btnSetTrue">Set True</button></td>
                               
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


@section('js')
<script type="text/javascript">

$('.btnSetTrue').on('click', function() {
    let question_id = $(this).attr('quesId');
    let answer_id = $(this).attr('ansId');
    
    $.ajax({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '{{ route('question.setAnswer') }}',
        type: 'POST',
        dataType: 'json',
        data: { 
            question_id : question_id,
            answer_id : answer_id
        },
        beforeSend: function() {
            $('.btnSetTrue').html('<i class="fas fa-circle-notch fa-spin"></i>');
        },
        complete: function() {
            $('.btnSetTrue').html('Set True');
        },
        success: function(json) {
            alert('Updated Succesfully!');            
            location.reload();            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
    
});

$('select[name=\'topic_id\']').trigger('change');
</script>
@stop