<x-app-layout>
    @slot('custom_style')
        <!-- DataTables -->
        <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <!-- Toastr -->
        <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    @endslot

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Role Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Role & Permission</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card card-maroon card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Role User</h3>
                            <div class="card-tools">
                                <a href="{{ route('roles.create') }}" class="btn btn-outline-success btn-xs"><i class="fas fa-user"></i> Add Role</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Role Name</th>
                                            <th>Role Permission</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roles as $key => $role)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    @if (!empty($role->getPermissionNames()))
                                                        @foreach ($role->getPermissionNames() as $v)
                                                            {{ $v }},
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <form onsubmit="return confirm('Are you sure ?');" action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-outline-info btn-xs"><i class="fas fa-edit"></i>Edit</a>
                                                        <button class="btn btn-outline-danger btn-xs" id="{{ $role->id }}-delete-btn"><i class="fa fa-trash-o m-r-5"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center text-sm" colspan="3">Not Available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>

    </section>
    <!-- /.content -->

    @slot('custom_script')
        <!-- bs-custom-file-input -->
        <script src="/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <!-- Treeview Js -->
        <script src="/plugins/bootstrap-treeview/js/bootstrap-treeview.js"></script>
        <!-- DataTables & Plugins -->
        <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="/plugins/jszip/jszip.min.js"></script>
        <script src="/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
        <!-- Toastr -->
        <script src="/plugins/toastr/toastr.min.js"></script>
        <script></script>
    @endslot
</x-app-layout>
