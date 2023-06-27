<x-app-layout>
    @slot('custom_style')
        <!-- Treeview -->
        <link rel="stylesheet" href="/plugins/bootstrap-treeview/css/bootstrap-treeview.css">
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
                    <h1>Production</h1>
                    <small>Cost Analysis</small>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Bill of Material SAP</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Tree View Cost Analysis</a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">expand</a>
                                </li> --}}
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false"></a>
                                </li> --}}
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Settings</a>
                                </li> --}}
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                    <form class="form-inline">
                                        <div class="form-group mb-2">
                                            <label for="" class="sr-only"></label>
                                            <input type="text" readonly class="form-control-plaintext" id="" value="Select SO Number">
                                        </div>
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="inputPassword2" class="sr-only">SO Number</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="filter_sono">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-info btn-flat" id="btn-filter"><i class="fas fa-search fa-fw"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-warning btn-sm mb-2" id="btn-reset"><i class="fas fa-sync fa-fw"></i></button>
                                    </form>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="socost-data" class="table table-sm table-bordered table-striped" cellspacing="0" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Item No</th>
                                                            <th>Item Description</th>
                                                            <th>Qty Order</th>
                                                            <th>Dimension</th>
                                                            <th>Material</th>
                                                            <th>Color</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <div id="treeview"></div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h3 class="card-title"><span id=""></span></h3>
                                                        </div>

                                                        <div class="card-body p-0">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="border-0">183</td>
                                                                    </tr>
                                                                    <tr data-widget="expandable-table" aria-expanded="true">
                                                                        <td>
                                                                            <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                                            219
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="expandable-body">
                                                                        <td>
                                                                            <div class="p-0">
                                                                                <table class="table table-hover">
                                                                                    <tbody>
                                                                                        {{-- sub 2-1 --}}
                                                                                        <tr data-widget="expandable-table" aria-expanded="false">
                                                                                            <td>
                                                                                                <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                                                                219-1
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr class="expandable-body">
                                                                                            <td>
                                                                                                <div class="p-0">
                                                                                                    <table class="table table-hover">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td>219-1-1</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td>219-1-2</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td>219-1-3</td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                        {{-- akhir sub 2-1 --}}
                                                                                        <tr data-widget="expandable-table" aria-expanded="false">
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-primary p-0">
                                                                                                    <i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                                                                                </button>
                                                                                                219-2
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr class="expandable-body">
                                                                                            <td>
                                                                                                <div class="p-0">
                                                                                                    <table class="table table-hover">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td>219-2-1</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td>219-2-2</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td>219-2-3</td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>219-3</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>657</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </div>
                                {{-- <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab"></div>
                                <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab"></div> --}}
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
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
        <script>
            $(document).ready(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('body').addClass('sidebar-collapse');

                function fill_sales_order(so_number = '') {
                    let table_data = $('#socost-data').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: "{{ route('sales_order.fetch') }}",
                            data: {
                                filter_so: so_number,
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'ItemAction',
                                name: 'ItemAction'
                            },
                            {
                                data: 'Dscription',
                                name: 'Dscription'
                            },
                            {
                                data: 'qty_order',
                                name: 'qty_order'
                            },
                            {
                                data: 'U_Dmsion',
                                name: 'U_Dmsion'
                            },
                            {
                                data: 'U_Material',
                                name: 'U_Material'
                            },
                            {
                                data: 'U_Color',
                                name: 'U_Color'
                            },
                        ],
                        columnDefs: [{
                            targets: [-1],
                            className: 'DT-left'
                        }],
                    });
                }

                $('#btn-filter').click(function() {
                    var so_number = $('#filter_sono').val();
                    $('#treeview').html('');
                    // alert(item_code);
                    if (so_number != '') {
                        $('#socost-data').DataTable().destroy();
                        fill_sales_order(so_number);
                        // fill_treeview(item_code)
                    } else {
                        toastr.info("Select SO number");
                    }
                });

                $('#btn-reset').click(function() {
                    $('#filter_sono').val('');
                    $('#socost-data').DataTable().destroy();
                    fill_sale_sorder();
                });

            });



            $(document).on('click', '.generate_bom', function() {
                var item_code = $(this).data('id');

                $.ajax({
                    url: "{{ route('cost-analysis.parent') }}",
                    type: "POST",
                    data: {
                        item_code: item_code,
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#treeview').html(result);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('Error: ' + errorThrown);
                    }
                });
            });

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
            }

            function fill_treeview(item_code) {
                $.ajax({
                    url: "{{ route('bom-treeview.show') }}",
                    type: "POST",
                    data: {
                        item_code: item_code,
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#treeview').treeview({
                            data: getTree()
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('Error: ' + errorThrown);
                    }
                });
            }

            function getTree() {
                // Some logic to retrieve, or generate tree structure
                var data = [{
                        text: "Parent 1",
                        nodes: [{
                                text: "Child 1",
                                nodes: [{
                                        text: "Grandchild 1"
                                    },
                                    {
                                        text: "Grandchild 2"
                                    }
                                ]
                            },
                            {
                                text: "Child 2"
                            }
                        ]
                    },
                    {
                        text: "Parent 2"
                    },
                    {
                        text: "Parent 3"
                    },
                    {
                        text: "Parent 4"
                    },
                    {
                        text: "Parent 5"
                    }
                ];
                return data;
            }
        </script>
    @endslot
</x-app-layout>
