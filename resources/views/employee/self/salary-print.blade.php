<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>EAMS | SALARY SLIP PRINT</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- Bootstrap 4 -->

        <!-- Font Awesome -->
        <link
            rel="stylesheet"
            href="/plugins/fontawesome-free/css/all.min.css"
        />
        <!-- Ionicons -->
        <link
            rel="stylesheet"
            href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"
        />
        <!-- Theme style -->
        <link rel="stylesheet" href="/dist/css/adminlte.min.css" />

        <!-- Google Font: Source Sans Pro -->
        <link
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700"
            rel="stylesheet"
        />
    </head>
    <body>
        <div class="wrapper">
            <!-- Main content -->
            <section class="invoice p-5">
                <!-- title row -->
                <div class="row">
                    <div class="col-12">
                        <h2 class="page-header">
                            Akademi Golf dan Kedi Falcon View Sdn. Bhd.
                            <small class="float-right">Date: {{$selected_payroll_month}}</small>
                        </h2>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info d-flex justify-contents-between">
                    <div class="col-sm-4 invoice-col">
                        To
                        <address>
                            <strong>{{$employee->first_name}} {{$employee->last_name}}</strong><br />
                            IC: {{$employee->ic}}<br />
                            Email: {{$employee->user->email}}
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 offset-sm-4 invoice-col">
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
            </section>
            <!-- /.content -->
        </div>
        <!-- ./wrapper -->

        <script type="text/javascript">
            window.addEventListener("load", window.print());
        </script>
    </body>
</html>
