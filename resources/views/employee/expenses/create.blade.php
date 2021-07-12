@extends('layouts.app')        

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Expense Claim</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('employee.index') }}">Employee Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Expense Claim
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
                <div class="col-md-4 mx-auto">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Expense Claim
                            </h3>
                        </div>
                        @include('messages.alerts')
                        <form 
                        action="{{ route('employee.expenses.store', $employee->id) }}" 
                        method="POST" 
                        enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Reason</label>
                                    <select name="reason" class="form-control" required>
                                        <option value="" {{ old('reason') != '' ? "" : "selected"}} disabled>Select reason</option>
                                        <option value="Transport" {{ old('reason') == 'Hospital' ? "selected" : ""}}>Transport</option>
                                        <option value="Hospital" {{ old('reason') == 'Hospital' ? "selected" : ""}}>Hospital</option>
                                        <option value="Bill" {{ old('reason') == 'Bill' ? "selected" : ""}}>Bill</option>
                                        <option value="Fuel" {{ old('reason') == 'Fuel' ? "selected" : ""}}>Fuel</option>
                                        <option value="Parking" {{ old('reason') == 'Parking' ? "selected" : ""}}>Parking</option>
                                        <option value="Equipment" {{ old('reason') == 'Equipment' ? "selected" : ""}}>Equipment</option>
                                    </select>
                                    @error('reason')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Amount</label>
                                    <input type="text" name="amount" value="{{ old('amount') }}" class="form-control">
                                    @error('amount')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Receipt Image</label>
                                    <input type="file" name="receipt" class="form-control-file">
                                    @error('receipt')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary" type="submit">Submit</button>
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

@section('extra-js')

<script>
    
</script>
@endsection