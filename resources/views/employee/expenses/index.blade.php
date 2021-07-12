@extends('layouts.app')        

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">List Expenses</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.index') }}">Employee Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            List Expenses
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
                <div class="col-md-8 mx-auto">
                    <!-- general form elements -->
                    @include('messages.alerts')
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">List of Expenses</h3>
                        </div>
                        <div class="card-body">
                            @if ($expenses->count())
                            <table class="table table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Applied on</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th class="none">Description</th>
                                        <th class="none">Remarks</th>
                                        <th class="none">Receipt</th>
                                        <th class="none">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $index => $expense)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $expense->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $expense->reason }}</td>
                                        <td>
                                            <h5>
                                                <span 
                                                @if ($expense->status == 'pending')
                                                    class="badge badge-pill badge-warning"
                                                @elseif($expense->status == 'declined')
                                                    class="badge badge-pill badge-danger"
                                                @elseif($expense->status == 'approved')
                                                    class="badge badge-pill badge-success"
                                                @endif
                                                >
                                                    {{ ucfirst($expense->status) }}
                                                </span> 
                                            </h5>
                                        </td>
                                        <td>{{ $expense->amount }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td>{{ $expense->remarks }}</td>
                                        <td>
                                            @if ($expense->receipt)
                                            <button type="button" class="btn btn-flat btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{ $index + 1 }}">
                                                View Receipt
                                            </button>  
                                            @else
                                            No receipt uploaded
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.expenses.edit', $expense->id) }}" class="btn btn-flat btn-warning {{$expense->status == 'approved' ? "disabled" : ""}}">Edit</a>
                                            <button type="button" class="btn btn-flat btn-danger" 
                                            data-toggle="modal" 
                                            data-target="#deleteModalCenter{{ $index + 1 }}" {{$expense->status == 'approved' ? "disabled" : ""}}
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @foreach($expenses as $index => $expense)
                                @if ($expense->receipt )
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter{{ $index+1 }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle{{ $index+1 }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body text-center">
                                                    <img src="{{Storage::url("/receipts/{$expense->receipt}")}}" class="img-fluid" alt=""
                                                    style="box-shadow: 2px 4px rgba(0,0,0,0.1)"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.modal -->
                                @endif
                                <!-- Modal -->
                                <div class="modal fade" id="deleteModalCenter{{ $index+1 }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle1{{ $index+1 }}" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="card card-danger">
                                                <div class="card-header">
                                                    <h5 style="text-align: center !important">Are you sure want to delete?</h5>
                                                </div>
                                                <div class="card-body text-center d-flex" style="justify-content: center">
                                                    
                                                    <button type="button" class="btn flat btn-secondary" data-dismiss="modal">No</button>
                                                    
                                                    <form 
                                                    action="{{ route('employee.expenses.delete', $expense->id) }}"
                                                    method="POST"
                                                    >
                                                    @csrf
                                                    @method('DELETE')
                                                        <button type="submit" class="btn flat btn-danger ml-1">Yes</button>
                                                    </form>
                                                </div>
                                                <div class="card-footer text-center">
                                                    <small>This action is irreversable</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal -->
                            @endforeach
                            @else
                            <div class="alert alert-info text-center" style="width:50%; margin: 0 auto">
                                <h4>No records available</h4>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

@section('extra-js')

<script>
$(document).ready(function(){
    
    $('#dataTable').DataTable({
        responsive:true,
        autoWidth: false
    });
    
});
</script>
@endsection