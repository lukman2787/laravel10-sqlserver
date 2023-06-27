<x-app-layout>
    @slot('custom_style')
        <!-- Treeview -->
        <link rel="stylesheet" href="/plugins/bootstrap-treeview/css/bootstrap-treeview.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    @endslot

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Production</h1>
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
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Bill Of Material details</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <div class="card-body">
                            <form class="form-horizontal">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Item Number/Code</label>
                                            <div class="col-sm-6">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control form-control-sm" id="item_code">
                                                    <span class="input-group-append">
                                                        <button type="button" class="btn btn-success btn-flat" id="filter"><i class="fa fa-search"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-outline-info btn-block btn-sm"><i class="fa fa-book"></i> Get Item</button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="ItemDescription" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Desc. In Foreign Lang</label>
                                            <div class="col-sm-8">
                                                <textarea type="text" class="form-control form-control-sm" id="FrgnName" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Item Group</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="GroupName" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Unit Price</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-sm" id="price" readonly>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="curr" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Dimension</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="u_dimension" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Material</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="u_material" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Color</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm " id="u_color" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Picture Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="PictureName" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-4 col-form-label">Picture Path</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="PicturePath" value="X:\java\gbsap\Pict" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <hr>
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="card card-primary card-outline card-outline-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Tree View Bill Of Materials</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Component Bill Of Materials</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Picture</a>
                                                </li>
                                                {{-- <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Settings</a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div id="treeview"></div>

                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table id="gridview-bom" class="table table-sm table-bordered table-striped" cellspacing="0" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Item</th>
                                                                        <th>Item Description</th>
                                                                        <th>UoM</th>
                                                                        <th>Quantity</th>
                                                                        <th>Whse</th>
                                                                        <th>Curr</th>
                                                                        <th>Price</th>
                                                                        <th>Dept</th>
                                                                        <th>Bom type</th>
                                                                        <th>Group Name</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                                    Picture Here
                                                </div>
                                                {{-- <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                                    
                                                </div> --}}
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                        </div>

                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->

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
        <script>
            $(document).ready(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('body').addClass('sidebar-collapse');

                $(function() {
                    bsCustomFileInput.init();
                });

                // $('#treeview').treeview({
                //     data: getTree()
                // });

                function fill_grideview_bom(item_code = '') {
                    let table_data = $('#gridview-bom').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: "{{ route('get_bom') }}",
                            data: {
                                filter_item: item_code,
                            }
                        },
                        // createdRow: function(row, data, dataIndex) {
                        //     if (data.Price !== undefined) {
                        //         // 4 here is the cell number, it starts from 0 where this number should appear
                        //         $(row).find('td:eq(7)').html(formatNumber(data.Price));
                        //     }
                        // },
                        // pageLength: 25,
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'Item',
                                name: 'Item'
                            },
                            {
                                data: 'ItemDescription',
                                name: 'ItemDescription'
                            },
                            {
                                data: 'UoM',
                                name: 'UoM'
                            },
                            {
                                data: 'qty',
                                name: 'qty'
                            },
                            {
                                data: 'Whse',
                                name: 'Whse'
                            },
                            {
                                data: 'Curr',
                                name: 'Curr'
                            },
                            {
                                data: 'Price',
                                name: 'Price'
                            },
                            {
                                data: 'Depth',
                                name: 'Depth'
                            },
                            {
                                data: 'BOMType',
                                name: 'BOMType'
                            },
                            {
                                data: 'GroupName',
                                name: 'GroupName'
                            },
                        ],
                        columnDefs: [{
                            targets: [-1],
                            className: 'DT-left'
                        }],
                    });
                }

                $('#filter').click(function() {
                    var item_code = $('#item_code').val();
                    // alert(item_code);
                    if (item_code != '') {
                        bom_item_description(item_code);
                        $('#gridview-bom').DataTable().destroy();
                        fill_grideview_bom(item_code);
                        fill_treeview(item_code)
                    } else {
                        alert('Select Item Code filter option');

                    }
                });

                $('#reset').click(function() {
                    $('#item_code').val('');
                    $('#gridview-bom').DataTable().destroy();
                    fill_grideview_bom();
                });

            });

            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
            }

            function fill_treeview(item_code = '') {
                $.ajax({
                    url: "{{ route('bom-treeview.show') }}",
                    type: "POST",
                    data: {
                        item_code: item_code,
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#treeview').treeview({
                            data: result,
                        });
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('Error: ' + errorThrown);
                    }
                });
            }

            function bom_item_description(item_code) {
                $.ajax({
                    url: "{{ route('bom-item.show') }}",
                    type: "POST",
                    data: {
                        item_code: item_code,
                    },
                    dataType: 'json',
                    success: function(result) {
                        // $('#item_description').val(data.ItemName);
                        $.each(result.item_description, function(key, value) {
                            $('#ItemDescription').val(value.ItemName);
                            $('#FrgnName').val(value.FrgnName);
                            $('#GroupName').val(value.GroupName);
                            $('#price').val(value.Price);
                            $('#curr').val(value.Curr);
                            $('#u_dimension').val(value.U_Dmsion);
                            $('#u_material').val(value.U_Material);
                            $('#u_color').val(value.U_Color);
                            $('#PictureName').val(value.PicturName);
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
