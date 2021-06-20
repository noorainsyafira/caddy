@extends('layouts.app')        

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">List of Employees</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.index') }}">Admin Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            List of Employees
                        </li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if($isGenerated == 'false')
            <div class="row">
                <div class="col-lg callout callout-warning">
                    <h5><i class="fas fa-warning"></i> Note:</h5>
                    The employee payroll for <b>{{$selected_payroll_month}}</b> have not been <b>fully generated</b>. 
                </div>
            </div>
            @endif
            @include('messages.alerts')
            <div class="row">
                <div class="col-md-3 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            Select Payroll Month
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto text-center">
                                    <form action="{{ route('admin.payroll.employees') }}" method="POST">
                                        @csrf
                                        <fieldset>
                                            <div class="form-group">
                                                <label for="">Month</label>
                                                <select name="payroll_month" class="form-control" id="payroll_month" required>
                                                    <option value="" selected hidden>Select Month</option>
                                                    @forelse($month_years as $value)
                                                        <option value="{{$value}}" @if($selected_payroll_month == $value) selected @endif>{{$value}}</option>
                                                    @empty
                                                        <option value="" disabled>No Payslip Available</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </fieldset>
                                        
                                        <input type="submit" name="" class="btn btn-primary" value="Submit">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($employees)
            <div class="row">
                <div class="col-lg mx-auto">
                    <form 
                        action="{{ route('admin.payroll.generate.list') }}"
                        method="POST"
                    >
                        @csrf
                        <input type="hidden" name="selected_payroll_month" value="{{$selected_payroll_month}}">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="card-title w-100">
                                    Employee Payroll 
                                    @if($selected_payroll_month)
                                    of <span class="badge badge-pill badge-success">{{ $selected_payroll_month }}</span>
                                    @endif
                                    <button type="submit" class="btn btn-flat btn-success float-right">Generate from List</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>Gender</th>
                                            <th>Nationality</th>
                                            <th>Generated</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td class="text-center" style="width: 5%">
                                                <input type="checkbox" class="form-check" name="employees_checkbox[]" value="{{$employee->id}}" id="employee-{{$employee->id}}"/>
                                            </td>
                                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                            <td style="width: 10%">{{ $employee->sex }}</td>
                                            <td style="width: 10%">{{ $employee->nationality }}</td>
                                            <td style="width: 15%">
                                                <h5 class="text-center">
                                                    <span class="badge badge-pill {{$employee->payroll == null ? 'badge-danger' : 'badge-success'}}">
                                                        {{$employee->payroll == null ? 'Not Generated' : 'Generated'}}
                                                    </span>
                                                </h5>
                                            </td>
                                            <td class="text-center" style="width: 8%">
                                                @if($employee->payroll)
                                                <button
                                                    class="btn btn-flat btn-primary"
                                                    data-toggle="modal"
                                                    data-target="#viewModalCenter{{ $employee->id }}"
                                                    onclick="event.preventDefault()"
                                                >View</button>
                                                @else
                                                <button data-id="{{$employee->id}}" class="btn btn-flat btn-success btn-generate">Generate</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <!-- general form elements -->
                </div>
            </div>
            <form 
                action="{{ route('admin.payroll.generate') }}"
                method="POST"
                id="generate-single"
            >
                @csrf
                <input type="hidden" name="payroll_month" value="{{$selected_payroll_month}}">
                <input type="hidden" name="employee_id" id="employee_id" value="">
            </form>
            @foreach($employees as $employee)
                <!-- Modal -->
                <div class="modal fade" id="viewModalCenter{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalCenterTitle1{{ $employee->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
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
                                                        <a
                                                            href="{{ route('employee.self.salary_slip_print') }}"
                                                            target="_blank"
                                                            class="btn btn-default float-right"
                                                            ><i class="fas fa-print"></i>
                                                            Print</a
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal -->
            @endforeach
            @endif
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection
@section('extra-js')

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive:true,
            autoWidth: false,
        });

        $(document).on('click', '.btn-generate', function(e) {
            e.preventDefault()
            var id = $(this).data('id')
            $('#employee_id').val(id)

            $('#generate-single').submit()
        });
    });
</script>
@endsection