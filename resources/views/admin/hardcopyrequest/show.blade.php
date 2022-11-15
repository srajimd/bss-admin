@extends('adminlte::page')

@section('title', 'Admin | Hard Copy Request')

@section('content_header')
    <div class="row justify-content-between">
        <div class="col-4">
        <h1 class="m-0 text-dark">View Address</h1>
        </div>
        <div class="col-6 text-right">
            @php
            $qString = request()->query();            
            @endphp
            <a class="btn btn-primary btn-sm" href="{{ route('enrollments.index') }}">Back</a>            
        </div>
    </div>   
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hard Copy requested by <strong>{{ $hardcopyrequest->customer }}</strong></h3>
            </div>
            <div class="card-body table-responsive">                                                   
                <div class="row">
                    <div class="col-sm-12">                                               
                        <table class="table ">                           
                            <tbody>   
                                <tr><td><b>Requested Date</b></td><td>{{ date('d-m-Y', strtotime($hardcopyrequest->created_at)) }}</td></tr>                              
                                <tr><td><b>ID</b></td><td>#{{ $hardcopyrequest->id }}</td></tr>
                                <tr><td><b>Address</b></td><td>{{ $hardcopyrequest->address1 }}</td></tr> 
                                <tr><td><b>City</b></td><td>{{ $hardcopyrequest->city }}</td></tr>
                                <tr><td><b>State</b></td><td>{{ $hardcopyrequest->state }}</td></tr> 
                                <tr><td><b>Zipcode</b></td><td>{{ $hardcopyrequest->zipcode }}</td></tr>                              
                                <tr><td><b>Country</b></td><td>{{ $hardcopyrequest->country }}</td></tr> 
                                <tr><td><b>Email Address</b></td><td>{{ $hardcopyrequest->email }}</td></tr>
                                <tr><td><b>Mobile Number</b></td><td>{{ $hardcopyrequest->mobile }}</td></tr>                                                   
                            </tbody>
                        </table>                       
                    </div>                   
                </div>                                        
            </div>             
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Paid Details</h3>
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-sm-12">                                               
                        <table class="table">                           
                            <tbody>
                                <tr><td><b>Amount</b></td><td>{{ $hardcopyrequest->amount }}</td></tr>
                                <tr>
                                    <td><b>Status</b></td>
                                    <td>
                                        @if( $hardcopyrequest->status == 1)
                                        <label class="badge badge-success ml-4">Paid</label>
                                        @else
                                        <label class="badge badge-danger ml-4">Pending</label>
                                        @endif
                                    </td>
                                </tr>
                                <tr><td><b>Transaction ID</b></td><td>{{ $hardcopyrequest->transaction_id }}</td></tr>
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@stop               