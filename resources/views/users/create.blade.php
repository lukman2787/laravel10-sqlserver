<x-app-layout>
    @slot('custom_style')
        <!-- Select2 -->
        <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    @endslot

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        User
                        <small>Add new user</small>
                    </h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">Settings</a>
                        </li>
                        <li class="breadcrumb-item active">Add User</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Form Add user</h3>
                            <div class="card-tools">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-warning btn-xs"><i class="fas fa-undo"></i> Back</a>
                            </div>
                        </div>
                        <form action="{{ route('users.store') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input class="form-control form-control-sm @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Username <span class="text-danger">*</span></label>
                                                    <input class="form-control form-control-sm @error('username') is-invalid @enderror" type="text" name="username" id="username" value="{{ old('username') }}">
                                                    @error('username')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Email <span class="text-danger">*</span></label>
                                                    <input class="form-control form-control-sm @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{ old('email') }}">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input class="form-control form-control-sm @error('password') is-invalid @enderror" type="password" name="password" id="password">
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Confirm Password</label>
                                                    <input class="form-control form-control-sm @error('confirm-password') is-invalid @enderror" type="password" name="confirm-password" id="confirm-password">
                                                    @error('confirm-password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Role</label>
                                                    <select class="select2 @error('roles') is-invalid @enderror" name="roles[]" multiple="multiple" style="width: 100%;">
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('roles')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-default"><i class="fas fa-recycle"></i> Cancel</a>
                            </div>
                            <!-- /.card-footer -->
                        </form>

                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>

    </section>
    <!-- /.content -->

    @slot('custom_script')
        <!-- Select2 -->
        <script src="/plugins/select2/js/select2.full.min.js"></script>
        <script>
            $(function() {
                //Initialize Select2 Elements
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                });
            })
        </script>
    @endslot
</x-app-layout>
