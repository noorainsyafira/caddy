@extends('layouts.app')        

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Register Attendance</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.index') }}">Employee Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Register Attendance
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
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Today's Attendance</h3>
                        </div>
                        <!-- /.card-header -->
                        @include('messages.alerts')
                        <!-- form start -->
                        <form role="form" method="post" action="{{ route('employee.attendance.store', $employee->id) }}" >
                            @csrf
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="entry_time">Entry Time</label>
                                            <select class="form-control" name="attendance_shift" id="attendance_shift" required>
                                                <option value="" selected hidden>Select Shift</option>
                                                <option value="morning">Morning Shift</option>
                                                <option value="afternoon">Afternoon Shift</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="entry_location">Entry Location</label>
                                            <select class="form-control" name="attendance_rounds" id="attendance_rounds" required>
                                                <option value="" selected hidden>Select Rounds</option>
                                                <option value="half">Half Rounds / 9 Holes</option>
                                                <option value="full">Full Rounds / 18 Holes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer" >
                                <button type="submit" class="btn btn-primary p-3" style="font-size:1.2rem">
                                    Record Attendance
                                </button> 
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection