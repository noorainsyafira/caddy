@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Invoice</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="#">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Invoice
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        Select Payroll Month
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mx-auto text-center">
                                <form action="{{ route('employee.self.salary_slip') }}" method="POST">
                                    @csrf
                                    <fieldset>
                                        <div class="form-group">
                                            <label for="">Month</label>
                                            <select id="payroll_months" name="payroll_months" class="form-control">
                                                <option value="" selected hidden>Select Month</option>
                                                @forelse($payroll_months as $month)
                                                    <option value="{{$month->month_year}}">{{$month->month_year}}</option>
                                                @empty
                                                    <option value="" disabled>No Payslip Available</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </fieldset>
                                    
                                    <button id="btn-view" data-id="" class="btn btn-flat btn-primary">View</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($selected_payroll_month)
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h5 style="text-align: center !important">Payslip for {{$selected_payroll_month}}</h5>
                    </div>
                    <div class="card-body" style="justify-content: center">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="callout callout-info">
                                    <h5><i class="fas fa-info"></i> Note:</h5>
                                    This page has been enhanced for printing.
                                    Click the print button at the bottom of the
                                    invoice to test.
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice p-3 mb-3">
                                    <!-- title row -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>
                                                Akademi Golf dan Kedi Falcon View Sdn. Bhd.
                                                <small class="float-right">Date: {{$selected_payroll_month}}</small
                                                >
                                            </h4>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- info row -->
                                    <div class="row invoice-info d-flex justify-contents-between">
                                        <!-- /.col -->
                                        <div class="col-lg-4 invoice-col">
                                            To
                                            <address>
                                                <strong>{{$employee->first_name}} {{$employee->last_name}}</strong><br />
                                                IC: {{$employee->ic}}<br />
                                                Email: {{$employee->user->email}}
                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-lg-4 offset-lg-4 invoice-col">
                                            <b>Employer No.:</b> {{$employer_no}}<br />
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                    <!-- Table row -->
                                    <div class="row">
                                        <div class="col-12 table-responsive">
                                            <table class="table table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4" style="width: 80%">Earnings</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Shift</th>
                                                        <th>Rounds</th>
                                                        <th style="width: 20%">MYR</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($employee->attendancesToday as $attendance)
                                                    <tr>
                                                        <td>{{$attendance->date}}</td>
                                                        <td>{{ucfirst($attendance->shift)}}</td>
                                                        <td>{{ucfirst($attendance->rounds)}}</td>
                                                        <td>{{$attendance->earnings}}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="alert alert-info text-center" style="width:50%; margin: 0 auto">
                                                                <h4>No Records Available</h4>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3"><b>Net Pay<b></td>
                                                        <td><b>{{$employee->total_earnings}}</b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                
                                    <!-- this row will not appear when printing -->
                                    <div class="row no-print">
                                        <div class="col-12">
                                            <form action="{{ route('employee.self.salary_slip_print') }}">
                                                @csrf
                                                <input type="hidden" name="payroll_month" value="{{$selected_payroll_month}}">
                                                <button
                                                    target="_blank"
                                                    class="btn btn-default float-right"
                                                >
                                                    <i class="fas fa-print"></i>
                                                    Print
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection



