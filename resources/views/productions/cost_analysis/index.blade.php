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
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">BoM Cost Analysis</a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Actual Cost</a>
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

                                    {{-- <form class="form-inline">
                                        <div class="form-group mb-2">
                                            <label for="" class="text-muted">Select SO Number</label>
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

                                        <div class="col-lg-4">
                                            <p>Update : {{ date('d F Y', strtotime(date('Y-m-d'))) }}</p>
                                        </div>
                                    </form> --}}

                                    <div class="row">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="po_number">Select SO Number </label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" id="filter_sono">
                                                    <span class="input-group-append">
                                                        <button type="button" class="btn btn-info btn-flat" id="btn-filter"><i class="fas fa-search fa-fw"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <button type="button" class="btn bg-gradient-danger btn-sm" id="btn-reset-po" style="margin-top:30px">
                                                    <i class="fas fa-sync fa-fw"></i>
                                                </button>
                                                <button type="submit" name="download_excel_po" id="download_excel_po" class="btn bg-gradient-success btn-sm" style="margin-top:30px">
                                                    <i class="fas fa-file-excel"></i> To Excel
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group clearfix" style="margin-top:30px;">
                                                <div class="icheck-primary d-inline">
                                                    <p>Price Update : {{ date('d F Y', strtotime(date('Y-m-d'))) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="table-responsive">
                                                <table id="socost-data" class="table table-sm table-bordered table-striped" cellspacing="0" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="7" class="text-center">Product Sales Properties</th>
                                                            <th colspan="4" class="text-center">Cost Analysis</th>
                                                            <th colspan="4" class="text-center">GP Analysis</th>
                                                        </tr>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Item No</th>
                                                            <th>Item Description</th>
                                                            <th>Qty Order</th>
                                                            <th>Dimension</th>
                                                            <th>Material</th>
                                                            <th>Color</th>
                                                            <th>Plan Cost (BOM)</th>
                                                            <th>Actual Cost</th>
                                                            <th>Cost Diff</th>
                                                            <th class="text-center"><i class="fas fa-download"></i></th>
                                                            <th class="text-center">Plan GP</th>
                                                            <th class="text-center">Actual GP</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- <div class="col-xl-4">
                                            <div id="card-image" class="d-none">
                                                <div class="card-header">Item Picture</div>
                                                <div class="card mb-2 bg-gradient-dark">
                                                    <div id="view-picture">
                                                        <img class="card-img-top" src="/storage/no-image-item.png" alt="Product Photo">
                                                    </div>
                                                    <div class="card-img-overlay d-flex flex-column justify-content-end">
                                                        <h5 class="card-title text-primary"></h5>
                                                        <p class="card-text pb-2 pt-1"></p>
                                                        <a href="#"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
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
                                                                                        <tr data-widget="expandable-table" aria-expanded="true">
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
                        // responsive: true,
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
                            {
                                data: 'resume_plan_cost',
                                name: 'resume_plan_cost'
                            },
                            {
                                data: 'resume_actual_cost',
                                name: 'resume_actual_cost'
                            },
                            {
                                data: 'diff_cost',
                                name: 'diff_cost'
                            },
                            {
                                data: 'DownloadAction',
                                name: 'DownloadAction'
                            },
                            {
                                data: 'plan_gp',
                                name: 'plan_gp'
                            },
                            {
                                data: 'actual_gp',
                                name: 'actual_gp'
                            },
                        ],
                        columnDefs: [{
                            targets: [-1],
                            className: 'DT-center'
                        }],
                    });
                }

                $('#btn-filter').click(function() {
                    var so_number = $('#filter_sono').val();
                    $('#treeview').html('');
                    // $('#card-image').html('');
                    $('#card-image').addClass('d-none');
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

                function buildTree(array, parent) {
                    var tree = [];

                    for (var i = 0; i < array.length; i++) {
                        if (array[i].parent === parent) {
                            var children = buildTree(array, array[i].id);
                            if (children.length) {
                                array[i].nodes = children;
                            }
                            tree.push(array[i]);
                        }
                    }

                    return tree;
                }

                var data = [{
                        id: 'RK-J21067CRS1',
                        ItemDescription: 'Rangka Juliette Rectangle Stool With Storage 42x21x42',
                        parent: 'J21067CRS1-CN3B507PJW',
                        BOMType: 'P',
                        nodes: [{
                                id: 'RKK-RW-J21067CRS1',
                                ItemDescription: 'Rangka Kayu Manium/mungur/Afrika (Oven) Juliette Rectangle Stool With Storage Uk Jadi setelah coco 42x21x42(Pake Karton)',
                                parent: 'RK-J21067CRS1',
                                BOMType: 'N'
                            },
                            {
                                id: 'LEM-FOX-PTH',
                                ItemDescription: 'Lem Fox Putih 800gr (ktg orage)',
                                parent: 'RK-J21067CRS1',
                                BOMType: 'N'
                            },
                            {
                                id: 'PW-3',
                                ItemDescription: 'Ply wood 122x244x3 mm meranti,mc/palm (t=2.5-2.7mm)',
                                parent: 'RK-J21067CRS1',
                                BOMType: 'N'
                            }
                        ]
                    },
                    {
                        id: 'RNDM-STOOL-DIA 40',
                        ItemDescription: 'Rendam Stool Dia 40/Square Stool 42x42',
                        parent: 'J21067CRS1-CN3B507PJW',
                        BOMType: 'P',
                        nodes: [{
                                id: 'LNTRK',
                                ItemDescription: 'Agenda 25 EC /1 ltr',
                                parent: 'RNDM-STOOL-DIA 40',
                                BOMType: 'N'
                            },
                            {
                                id: 'LB-RNDM-STL-D40',
                                ItemDescription: 'Rendam Stool Dia 40 /Square stool 42x42',
                                parent: 'RNDM-STOOL-DIA 40',
                                BOMType: 'N'
                            }
                        ]
                    },
                    {
                        id: 'LBR-POP-STL',
                        ItemDescription: 'Labur Pop Stool/goni stool/envi mosaic Stl/Asela stool',
                        parent: 'J21067CRS1-CN3B507PJW',
                        BOMType: 'P',
                        nodes: [{
                                id: 'LNTRK',
                                ItemDescription: 'Agenda 25 EC /1 ltr',
                                parent: 'LBR-POP-STL',
                                BOMType: 'N'
                            },
                            {
                                id: 'KUAS-2',
                                ItemDescription: 'Kuas 2',
                                parent: 'LBR-POP-STL',
                                BOMType: 'N'
                            },
                            {
                                id: 'SOLAR',
                                ItemDescription: 'SOLAR',
                                parent: 'LBR-POP-STL',
                                BOMType: 'N'
                            },
                            {
                                id: 'LB-LBR-POP-STL',
                                ItemDescription: 'Labur Pop Stool/goni stool/emvi mosaic stool/asela stool',
                                parent: 'LBR-POP-STL',
                                BOMType: 'N'
                            }
                        ]
                    }
                ];

                var treeviewData = buildTree(data, null);

                function generateTreeView(data) {
                    var html = '<ul>';

                    for (var i = 0; i < data.length; i++) {
                        html += '<li>';
                        html += data[i].ItemDescription;

                        if (data[i].nodes && data[i].nodes.length) {
                            html += generateTreeView(data[i].nodes);
                        }

                        html += '</li>';
                    }

                    html += '</ul>';

                    return html;
                }

                var treeview = generateTreeView(treeviewData);
                // console.log(treeview);
            });



            $(document).on('click', '.generate_bom', function() {
                var item_code = $(this).data('id');
                // $(this).closest('tr').addClass('removeRow');
                $('#card-image').removeClass('d-none');

                $.ajax({
                    url: "{{ route('cost-analysis.show') }}",
                    type: "POST",
                    data: {
                        item_code: item_code,
                    },
                    dataType: 'json',
                    success: function(result) {
                        load_picture(item_code)
                        $('#treeview').html(result);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('Error: ' + errorThrown);
                    }
                });
            });

            $(document).on('click', '.downloadFile', function() {
                var item_code = $(this).data('item');
                console.log(item_code);
                var data = {
                    item_code: item_code,
                }

                var url = "{{ URL::to('production/cost-analysis/export-costAnalysis') }}?" + $.param(data)

                window.location = url;
                // $.ajax({
                //     url: "{{ route('costAnalysis.export') }}",
                //     type: "POST",
                //     // dataType: "json",
                //     data: {
                //         item_code: item_code,
                //     },
                //     success: function(result) {
                //         // window.location.href = result.file_url;
                //         // alert(item_code)
                //     },
                //     error: function(xhr, textStatus, errorThrown) {
                //         console.log('Error: ' + errorThrown);
                //     }

            });

            function downloadFile(url) {
                window.location.href = url;
            }

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
            }

            function load_picture(item_code) {
                $.ajax({
                    url: "{{ route('single-picture.load') }}",
                    method: "POST",
                    data: {
                        item_code: item_code
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data.ItemPicture);
                        $('#view-picture').html(data.ItemPicture);
                    }
                })
            }
        </script>
    @endslot
</x-app-layout>
