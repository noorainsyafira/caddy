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
                            <a href="{{ route('admin.payroll.report-month') }}">Payroll Report by Month</a> 
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
            @if ($payrolls)
            <div class="row">
                <div class="col-lg mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title w-100">
                                Monthly Payroll 
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Salary Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($payrolls as $payroll)
                                    @if ($payroll['payment'] != 0)
                                        <tr>
                                            <td>{{ $payroll['month'] }}</td>
                                            <td>RM{{ $payroll['payment'] }}</td>
                                        </tr>                                        
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- general form elements -->
                </div>
            </div>
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