@extends('layouts.app')        

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Payroll Report {{date('Y', strtotime(now()))}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.index') }}">Admin Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{ route('admin.payroll.report-employees') }}">Payroll Report by Employees</a> 
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
            @include('messages.alerts')
            @if ($employees)
            <div class="row">
                <div class="col-lg mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title w-100">
                                Employee Payroll 
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Gender</th>
                                        <th>Nationality</th>
                                        <th>Salary Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                        <td>{{ $employee->sex }}</td>
                                        <td>{{ $employee->nationality }}</td>
                                        <td>
                                            RM{{$employee->total_earnings}}
                                        </td>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- general form elements -->
                </div>
            </div>
            <form 
                action="{{ route('admin.payroll.generate') }}"
                method="POST"
                id="generate-single"
            >
                @csrf
                <input type="hidden" name="employee_id" id="employee_id" value="">
            </form>
            @foreach($employees as $employee)
                <!-- Modal -->
                <div class="modal fade" id="viewModalCenter{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalCenterTitle1{{ $employee->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="card card-info">
                                <div class="card-header">
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
                                                        <form action="{{ route('admin.salary_slip_print') }}">
                                                            @csrf
                                                            <input type="hidden" name="employee_id" id="employee_id" value="">
                                                            <button target="_blank" class="btn btn-default float-right">
                                                                <i class="fas fa-print"></i> Print
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

    function view(id){
        event.preventDefault();
        $('input[name="employee_id"]').val(id);
        console.log(id)
    }
</script>
@endsection