@extends('adminlte::page')

@section('title', 'Admin | Enrollments Management')

<style type="text/css">
.tooltip-inner {
  background-color: #28a745 !important;
  box-shadow: 0px 0px 4px black;
}

.tooltip.bs-tooltip-top .arrow:before {
 border-top-color:  #28a745 !important;
}
.modal-content img{
    max-width:100%;
}
.hide{
    display:none !important;
}

</style>

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
                                        <th>@sortablelink('name', 'Course')</th>
                                        <!--th>@sortablelink('name', 'email')</th-->
                                        <!--th>@sortablelink('duration', 'Duration (days)')</th-->
                                        <th width="15%">Amount & Status</th> 
                                        <th>@sortablelink('created_at', 'Enrollment Date')</th>
                                        <th width="10%">@sortablelink('expiry_date', 'Expiry Date')</th>
                                        <th>Hard Copy Request</th>  
                                        <th width="10%">Action</th>                                       
                                    </tr>
                                </thead>
                                <tbody> 
                                    @if( $enrollments->total() > 0 )
                                    @foreach ($enrollments as $key => $enrollment)
                                    <tr>                                    
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        {{ $enrollment->customer }}
                                        <p>{{ $enrollment->email }}</p>
                                    </td>
                                    <td>
                                        {{ $enrollment->name }}
                                        <p>Duration: {{ $enrollment->duration }} days</p>
                                    </td>
                                    <!--td>{{ $enrollment->email }}</td-->
                                    <!--td>{{ $enrollment->duration }}</td-->
                                    <td>{{ number_format($enrollment->amount, 2, '.','') }}
                                        @php
                                            if($enrollment->status):
                                                $paiddisp = '';
                                                $pendingdisp = 'hide';                                  
                                            else:
                                                $paiddisp = 'hide';
                                                $pendingdisp = ''; 
                                            endif;
                                        @endphp
                                        <label class="badge badge-success ml-4 paid_badge {{ $paiddisp }}" @if($enrollment->transaction_id) data-toggle="tooltip"   data-placement="top" title="Transaction ID: {{ $enrollment->transaction_id }}" @endif>Paid</label>
                                        <label class="badge badge-danger ml-4 pending_badge {{ $pendingdisp }} ">Pending</label>
                                    </td>
                                                                                                            
                                    <td>{{ date('d-m-Y', strtotime($enrollment->created_at)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($enrollment->expiry_date)) }}</td>
                                    <td class="text-center">
                                    @if($enrollment->is_hardcopy_requested == 'Y')
                                        @php
                                        $params = array('enrollment_id' => $enrollment->id);
                                        @endphp
                                        <a href="{{ route('showaddress', $params) }}" data-toggle="tooltip" data-placement="top" alt="View Address" title="View Address">
                                        <i class="fa fa-address-book" aria-hidden="true" style="color: #1d9d74;font-size: larger;"></i> 
                                        </a>
                                    @endif
                                    </td>
                                    <td class="text-center action-buttons">
                                    @if($enrollment->receipt)
                                        <a href="javascript:;" data-url="{{ str_replace('public/','',url('/').Storage::url('app/'.$enrollment->receipt)) }}" data-toggle="tooltip" data-placement="top" alt="View Receipt" title="View Receipt" class="viewfile" data-name="{{ $enrollment->customer }}">
                                        <i class="fas fa-file" aria-hidden="true" style="color: #1d9d74;font-size: larger;"></i> 
                                        </a>
                                    @endif 
                                    @if($enrollment->certificate)  
                                        <a href="javascript:;" data-url="{{ str_replace('public/','',url('/').Storage::url('app/'.$enrollment->certificate)) }}" data-toggle="tooltip" data-placement="top" alt="View Certificate" title="View Certificate" class="viewfile" data-name="{{ $enrollment->customer }}"><i class="fas fa-certificate ml-2" aria-hidden="true" style="color: #007bff;font-size: larger;"></i></a>
                                    @endif 

                                    @php
                                        if($enrollment->status==0 && $enrollment->receipt):
                                            $approvedisp = '';
                                            $disapprovedisp = 'hide';                                  
                                        else:
                                            $approvedisp = 'hide';
                                            $disapprovedisp = ''; 
                                        endif;
                                    @endphp
                                    <a href="javascript:;" class="updatestatus approveicon {{ $approvedisp }}" data-toggle="tooltip" data-placement="top" alt="Approve" title="Approve" data-status=1 data-eid={{ $enrollment->id }}><i class="fa fa-thumbs-up ml-2" aria-hidden="true" style="color: #007bff;font-size: larger;"></i></a>
                                    <a href="javascript:;" class="updatestatus disapproveicon {{ $disapprovedisp }}" data-toggle="tooltip" data-placement="top" alt="Disapprove" title="Disapprove" data-status=0 data-eid={{ $enrollment->id }}><i class="fa fa-thumbs-down ml-2" aria-hidden="true" style="color: #ff0000;font-size: larger;"></i></a>
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

@if( $enrollments->total() > 0 )
<div class="modal fade" id="viewfile" aria-hidden="true" tabindex="-1" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#ccc;padding:0.2rem 1rem;">
        <h5 class="modal-title"></h5>
        <a href="javascript:;" class="btn-close mt-2" data-dismiss="modal"><i class="fa fa-window-close"></i></a>
      </div>
      <div class="modal-body">        
      </div>
      <!--div class="modal-footer">
      </div-->
    </div>
  </div>
</div>
<div class="modal fade" id="hardcopy" aria-hidden="true" tabindex="-1" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <a href="javascript:;" class="btn-close" data-dismiss="modal"><i class="fa fa-close"></i></a>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

@endif

@section('css')    
    <link rel="stylesheet" href="{{url()->current()}}/../../vendor/dg-plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
@stop

@section('js')
    <script src="{{url()->current()}}/../../vendor/dg-plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            $(document).on('click', '.viewfile', function(){
                var title='';
                var img='<img src="'+$(this).data('url')+'"/>';
                if($(this).attr('title') == 'View Receipt'){
                    title="Receipt";                    
                }else{
                    title="Certificate";
                }
                $('#viewfile').find('.modal-title').html(title + " of <strong>" + $(this).data("name") + "</strong>");
                $('#viewfile').find('.modal-body').html(img);
                $('#viewfile').modal();
            });           

            $('.modal').on('hidden.bs.modal', function (e) {
                $(this).find('.modal-title').html('');
                $(this).find('.modal-body').html('');
            });

            $(document).on('click', '.updatestatus', function(){
                var thisObj = $(this);
                var status = thisObj.data('status');
                var enrollment_id = thisObj.data('eid');
                if(confirm("Are you sure to " + ((status==1)?"approve":"disapprove") + " this enrollment?")){
                    var jqxhr = $.ajax({
                        url: "{{ route('post.updatestatus') }}",
                        method: "POST",
                        data: {'status':status, "enrollment_id":enrollment_id},
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    });

                    jqxhr.done(function (response) {
                        if(response.status == 'success'){
                            thisObj.parents('.action-buttons').find('.approveicon').addClass('hide');
                            thisObj.parents('.action-buttons').find('.disapproveicon').addClass('hide');
                            thisObj.parents('tr').find('.paid_badge').addClass('hide');
                            thisObj.parents('tr').find('.pending_badge').addClass('hide');
                            if(status==1){                    
                                thisObj.parents('.action-buttons').find('.disapproveicon').removeClass('hide');
                                thisObj.parents('tr').find('.paid_badge').removeClass('hide');
                            }else{                                
                                thisObj.parents('.action-buttons').find('.approveicon').removeClass('hide');  
                                thisObj.parents('tr').find('.pending_badge').removeClass('hide');                              
                            }
                        }
                    });

                    jqxhr.fail(function (jqXHR, textStatus) {
                        console.log("Request failed: " + textStatus);
                    });
                }
            });

        });
     //Date range picker
    /*$('#start-date').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });*/
    </script>
@stop

