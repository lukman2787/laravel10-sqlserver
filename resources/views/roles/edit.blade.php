<x-app-layout>
    @slot('custom_style')
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    @endslot

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        Roles & Permission
                        <small>Add new role</small>
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
                        <li class="breadcrumb-item active">Add Role</li>
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
                            <h3 class="card-title">Form Add role</h3>
                            <div class="card-tools">
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-warning btn-xs"><i class="fas fa-undo"></i> Back</a>
                            </div>
                        </div>
                        <form action="{{ route('roles.update', $role->id) }}" method="post">
                            @method('PATCH')
                            @csrf
                            <div class="card-body">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Role <span class="text-danger">*</span></label>
                                            <input class="form-control form-control-sm @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') ?? $role->name }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive m-t-15">
                                                <table class="table table-striped table-sm">
                                                    <thead>
                                                        <tr class="table-info text-center">
                                                            <th>Module Permission</th>
                                                            <th class="text-center checkBox">
                                                                <div class="icheck-primary d-inline">
                                                                    <input type="checkbox" id="select_all_permission">
                                                                    <label for="select_all_permission">All</label>
                                                                </div>

                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($permission as $value)
                                                            <tr>
                                                                <td>{{ $value->name }}</td>
                                                                <td class="text-center checkBox">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="checkbox" class="checkPermission" name="permission[]" id="permission{{ $value->id }}" value="{{ $value->id }}" {{ in_array($value->id, $rolePermissions) ? 'checked' : null }}>
                                                                        <label for="permission{{ $value->id }}"></label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
                                <a href="{{ route('roles.index') }}" class="btn btn-default"><i class="fas fa-recycle"></i> Cancel</a>
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
        <script>
            $(document).on('click', '#select_all_permission', function() {
                if (this.checked) {
                    $('.checkPermission').each(function() {
                        this.checked = true;
                    })
                } else {
                    $('.checkPermission').each(function() {
                        this.checked = false;

                    })
                }
            });

            $(document).on('click', '.checkPermission', function() {
                if ($('.checkPermission:checked').length == $('.checkPermission').length) {
                    $('#select_all_permission').prop('checked', true);
                } else {
                    $('#select_all_permission').prop('checked', false);
                }
            });
        </script>
    @endslot
</x-app-layout>
