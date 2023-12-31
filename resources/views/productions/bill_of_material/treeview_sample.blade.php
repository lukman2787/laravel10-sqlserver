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
        <div class="container">
            <h1>Bootstrap Tree View</h1>
            <br>
            <div class="row">
                <div class="col-sm-4">
                    <h2>Default</h2>
                    <div id="treeview1"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Collapsed</h2>
                    <div id="treeview2"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Expanded</h2>
                    <div id="treeview3"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <h2>Blue Theme</h2>
                    <div id="treeview4"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Custom Icons</h2>
                    <div id="treeview5"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Tags as Badges</h2>
                    <div id="treeview6"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <h2>No Border</h2>
                    <div id="treeview7"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Colourful</h2>
                    <div id="treeview8"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Node Overrides</h2>
                    <div id="treeview9"></div>
                </div>
            </div>
            <div class="row">
                <hr>
                <h2>Searchable Tree</h2>
                <div class="col-sm-4">
                    <h2>Input</h2>
                    <!-- <form> -->
                    <div class="form-group">
                        <label for="input-search" class="sr-only">Search Tree:</label>
                        <input type="input" class="form-control" id="input-search" placeholder="Type to search..." value="">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-ignore-case" value="false">
                            Ignore Case
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-exact-match" value="false">
                            Exact Match
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-reveal-results" value="false">
                            Reveal Results
                        </label>
                    </div>
                    <button type="button" class="btn btn-success" id="btn-search">Search</button>
                    <button type="button" class="btn btn-default" id="btn-clear-search">Clear</button>
                    <!-- </form> -->
                </div>
                <div class="col-sm-4">
                    <h2>Tree</h2>
                    <div id="treeview-searchable"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Results</h2>
                    <div id="search-output"></div>
                </div>
            </div>
            <div class="row">
                <hr>
                <h2>Selectable Tree</h2>
                <div class="col-sm-4">
                    <h2>Input</h2>
                    <div class="form-group">
                        <label for="input-select-node" class="sr-only">Search Tree:</label>
                        <input type="input" class="form-control" id="input-select-node" placeholder="Identify node..." value="Parent 1">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-select-multi" value="false">
                            Multi Select
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-select-silent" value="false">
                            Silent (No events)
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-success select-node" id="btn-select-node">Select Node</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger select-node" id="btn-unselect-node">Unselect Node</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary select-node" id="btn-toggle-selected">Toggle Node</button>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h2>Tree</h2>
                    <div id="treeview-selectable"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Events</h2>
                    <div id="selectable-output"></div>
                </div>
            </div>
            <div class="row">
                <hr>
                <h2>Expandible Tree</h2>
                <div class="col-sm-4">
                    <h2>Input</h2>
                    <div class="form-group">
                        <label for="input-expand-node" class="sr-only">Search Tree:</label>
                        <input type="input" class="form-control" id="input-expand-node" placeholder="Identify node..." value="Parent 1">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-expand-silent" value="false">
                            Silent (No events)
                        </label>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success expand-node" id="btn-expand-node">Expand Node</button>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control" id="select-expand-node-levels">
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger expand-node" id="btn-collapse-node">Collapse Node</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary expand-node" id="btn-toggle-expanded">Toggle Node</button>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success" id="btn-expand-all">Expand All</button>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control" id="select-expand-all-levels">
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" id="btn-collapse-all">Collapse All</button>
                </div>
                <div class="col-sm-4">
                    <h2>Tree</h2>
                    <div id="treeview-expandible"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Events</h2>
                    <div id="expandible-output"></div>
                </div>
            </div>
            <div class="row">
                <hr>
                <h2>Checkable Tree</h2>
                <div class="col-sm-4">
                    <h2>Input</h2>
                    <div class="form-group">
                        <label for="input-check-node" class="sr-only">Search Tree:</label>
                        <input type="input" class="form-control" id="input-check-node" placeholder="Identify node..." value="Parent 1">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-check-silent" value="false">
                            Silent (No events)
                        </label>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success check-node" id="btn-check-node">Check Node</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger check-node" id="btn-uncheck-node">Uncheck Node</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary check-node" id="btn-toggle-checked">Toggle Node</button>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success" id="btn-check-all">Check All</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" id="btn-uncheck-all">Uncheck All</button>
                </div>
                <div class="col-sm-4">
                    <h2>Tree</h2>
                    <div id="treeview-checkable"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Events</h2>
                    <div id="checkable-output"></div>
                </div>
            </div>
            <div class="row">
                <hr>
                <h2>Disabled Tree</h2>
                <div class="col-sm-4">
                    <h2>Input</h2>
                    <div class="form-group">
                        <label for="input-disable-node" class="sr-only">Search Tree:</label>
                        <input type="input" class="form-control" id="input-disable-node" placeholder="Identify node..." value="Parent 1">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="checkbox" id="chk-disable-silent" value="false">
                            Silent (No events)
                        </label>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success disable-node" id="btn-disable-node">Disable Node</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger disable-node" id="btn-enable-node">Enable Node</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary disable-node" id="btn-toggle-disabled">Toggle Node</button>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success" id="btn-disable-all">Disable All</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" id="btn-enable-all">Enable All</button>
                </div>
                <div class="col-sm-4">
                    <h2>Tree</h2>
                    <div id="treeview-disabled"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Events</h2>
                    <div id="disabled-output"></div>
                </div>
            </div>
            <div class="row">
                <hr>
                <h2>Lifecycle Events</h2>
                <div class="col-sm-4">
                    <h2>Input</h2>
                    <div class="form-group">
                        <button type="button" class="btn btn-success lifecycle-events" id="btn-init" disabled>New Tree</button>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger lifecycle-events" id="btn-remove">Destroy</button>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h2>Tree</h2>
                    <div id="treeview-lifecycle"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Events</h2>
                    <div id="lifecycle-output"></div>
                </div>
            </div>
            <div class="row">
                <hr>
                <h2>Data Options</h2>
                <div class="col-sm-4">
                    <h2>Local JSON</h2>
                    <div id="treeview-json"></div>
                </div>
                <div class="col-sm-4">
                    <h2>Remote JSON</h2>
                    <div id="treeview-ajax"></div>
                </div>
                <div class="col-sm-4">
                    <h2></h2>
                </div>
            </div>
            <br />
            <br />
            <br />
            <br />
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
        {{-- <script src="./libs/jquery/jquery.js"></script>
        <script src="./js/bootstrap-treeview.js"></script> --}}
        <script type="text/javascript">
            $(function() {

                var defaultData = [{
                        text: 'Parent 1',
                        tags: ['4'],
                        nodes: [{
                                text: 'Child 1',
                                tags: ['2'],
                                nodes: [{
                                        text: 'Grandchild 1',
                                        tags: ['0']
                                    },
                                    {
                                        text: 'Grandchild 2',
                                        tags: ['0']
                                    }
                                ]
                            },
                            {
                                text: 'Child 2',
                                tags: ['0']
                            }
                        ]
                    },
                    {
                        text: 'Parent 2',
                        tags: ['0']
                    },
                    {
                        text: 'Parent 3',
                        tags: ['0']
                    },
                    {
                        text: 'Parent 4',
                        tags: ['0']
                    },
                    {
                        text: 'Parent 5',
                        tags: ['0']
                    }
                ];

                var alternateData = [{
                        text: 'Parent 1',
                        tags: ['2'],
                        nodes: [{
                                text: 'Child 1',
                                tags: ['3'],
                                nodes: [{
                                        text: 'Grandchild 1',
                                        tags: ['6']
                                    },
                                    {
                                        text: 'Grandchild 2',
                                        tags: ['3']
                                    }
                                ]
                            },
                            {
                                text: 'Child 2',
                                tags: ['3']
                            }
                        ]
                    },
                    {
                        text: 'Parent 2',
                        tags: ['7']
                    },
                    {
                        text: 'Parent 3',
                        icon: 'glyphicon glyphicon-earphone',
                        tags: ['11']
                    },
                    {
                        text: 'Parent 4',
                        icon: 'glyphicon glyphicon-cloud-download',
                        tags: ['19'],
                        selected: true
                    },
                    {
                        text: 'Parent 5',
                        icon: 'glyphicon glyphicon-certificate',
                        color: 'pink',
                        backColor: 'red',
                        tags: ['available', '0']
                    }
                ];

                var json = '[' +
                    '{' +
                    '"text": "Parent 1",' +
                    '"nodes": [' +
                    '{' +
                    '"text": "Child 1",' +
                    '"nodes": [' +
                    '{' +
                    '"text": "Grandchild 1"' +
                    '},' +
                    '{' +
                    '"text": "Grandchild 2"' +
                    '}' +
                    ']' +
                    '},' +
                    '{' +
                    '"text": "Child 2"' +
                    '}' +
                    ']' +
                    '},' +
                    '{' +
                    '"text": "Parent 2"' +
                    '},' +
                    '{' +
                    '"text": "Parent 3"' +
                    '},' +
                    '{' +
                    '"text": "Parent 4"' +
                    '},' +
                    '{' +
                    '"text": "Parent 5"' +
                    '}' +
                    ']';


                $('#treeview1').treeview({
                    data: defaultData
                });

                $('#treeview2').treeview({
                    levels: 1,
                    data: defaultData
                });

                $('#treeview3').treeview({
                    levels: 99,
                    data: defaultData
                });

                $('#treeview4').treeview({
                    color: "#428bca",
                    data: defaultData
                });

                $('#treeview5').treeview({
                    color: "#428bca",
                    expandIcon: 'glyphicon glyphicon-chevron-right',
                    collapseIcon: 'glyphicon glyphicon-chevron-down',
                    nodeIcon: 'glyphicon glyphicon-bookmark',
                    data: defaultData
                });

                $('#treeview6').treeview({
                    color: "#428bca",
                    expandIcon: "glyphicon glyphicon-stop",
                    collapseIcon: "glyphicon glyphicon-unchecked",
                    nodeIcon: "glyphicon glyphicon-user",
                    showTags: true,
                    data: defaultData
                });

                $('#treeview7').treeview({
                    color: "#428bca",
                    showBorder: false,
                    data: defaultData
                });

                $('#treeview8').treeview({
                    expandIcon: "glyphicon glyphicon-stop",
                    collapseIcon: "glyphicon glyphicon-unchecked",
                    nodeIcon: "glyphicon glyphicon-user",
                    color: "yellow",
                    backColor: "purple",
                    onhoverColor: "orange",
                    borderColor: "red",
                    showBorder: false,
                    showTags: true,
                    highlightSelected: true,
                    selectedColor: "yellow",
                    selectedBackColor: "darkorange",
                    data: defaultData
                });

                $('#treeview9').treeview({
                    expandIcon: "glyphicon glyphicon-stop",
                    collapseIcon: "glyphicon glyphicon-unchecked",
                    nodeIcon: "glyphicon glyphicon-user",
                    color: "yellow",
                    backColor: "purple",
                    onhoverColor: "orange",
                    borderColor: "red",
                    showBorder: false,
                    showTags: true,
                    highlightSelected: true,
                    selectedColor: "yellow",
                    selectedBackColor: "darkorange",
                    data: alternateData
                });



                var $searchableTree = $('#treeview-searchable').treeview({
                    data: defaultData,
                });

                var search = function(e) {
                    var pattern = $('#input-search').val();
                    var options = {
                        ignoreCase: $('#chk-ignore-case').is(':checked'),
                        exactMatch: $('#chk-exact-match').is(':checked'),
                        revealResults: $('#chk-reveal-results').is(':checked')
                    };
                    var results = $searchableTree.treeview('search', [pattern, options]);

                    var output = '<p>' + results.length + ' matches found</p>';
                    $.each(results, function(index, result) {
                        output += '<p>- ' + result.text + '</p>';
                    });
                    $('#search-output').html(output);
                }

                $('#btn-search').on('click', search);
                $('#input-search').on('keyup', search);

                $('#btn-clear-search').on('click', function(e) {
                    $searchableTree.treeview('clearSearch');
                    $('#input-search').val('');
                    $('#search-output').html('');
                });


                var initSelectableTree = function() {
                    return $('#treeview-selectable').treeview({
                        data: defaultData,
                        multiSelect: $('#chk-select-multi').is(':checked'),
                        onNodeSelected: function(event, node) {
                            $('#selectable-output').prepend('<p>' + node.text + ' was selected</p>');
                        },
                        onNodeUnselected: function(event, node) {
                            $('#selectable-output').prepend('<p>' + node.text + ' was unselected</p>');
                        }
                    });
                };
                var $selectableTree = initSelectableTree();

                var findSelectableNodes = function() {
                    return $selectableTree.treeview('search', [$('#input-select-node').val(), {
                        ignoreCase: false,
                        exactMatch: false
                    }]);
                };
                var selectableNodes = findSelectableNodes();

                $('#chk-select-multi:checkbox').on('change', function() {
                    console.log('multi-select change');
                    $selectableTree = initSelectableTree();
                    selectableNodes = findSelectableNodes();
                });

                // Select/unselect/toggle nodes
                $('#input-select-node').on('keyup', function(e) {
                    selectableNodes = findSelectableNodes();
                    $('.select-node').prop('disabled', !(selectableNodes.length >= 1));
                });

                $('#btn-select-node.select-node').on('click', function(e) {
                    $selectableTree.treeview('selectNode', [selectableNodes, {
                        silent: $('#chk-select-silent').is(':checked')
                    }]);
                });

                $('#btn-unselect-node.select-node').on('click', function(e) {
                    $selectableTree.treeview('unselectNode', [selectableNodes, {
                        silent: $('#chk-select-silent').is(':checked')
                    }]);
                });

                $('#btn-toggle-selected.select-node').on('click', function(e) {
                    $selectableTree.treeview('toggleNodeSelected', [selectableNodes, {
                        silent: $('#chk-select-silent').is(':checked')
                    }]);
                });



                var $expandibleTree = $('#treeview-expandible').treeview({
                    data: defaultData,
                    onNodeCollapsed: function(event, node) {
                        $('#expandible-output').prepend('<p>' + node.text + ' was collapsed</p>');
                    },
                    onNodeExpanded: function(event, node) {
                        $('#expandible-output').prepend('<p>' + node.text + ' was expanded</p>');
                    }
                });

                var findExpandibleNodess = function() {
                    return $expandibleTree.treeview('search', [$('#input-expand-node').val(), {
                        ignoreCase: false,
                        exactMatch: false
                    }]);
                };
                var expandibleNodes = findExpandibleNodess();

                // Expand/collapse/toggle nodes
                $('#input-expand-node').on('keyup', function(e) {
                    expandibleNodes = findExpandibleNodess();
                    $('.expand-node').prop('disabled', !(expandibleNodes.length >= 1));
                });

                $('#btn-expand-node.expand-node').on('click', function(e) {
                    var levels = $('#select-expand-node-levels').val();
                    $expandibleTree.treeview('expandNode', [expandibleNodes, {
                        levels: levels,
                        silent: $('#chk-expand-silent').is(':checked')
                    }]);
                });

                $('#btn-collapse-node.expand-node').on('click', function(e) {
                    $expandibleTree.treeview('collapseNode', [expandibleNodes, {
                        silent: $('#chk-expand-silent').is(':checked')
                    }]);
                });

                $('#btn-toggle-expanded.expand-node').on('click', function(e) {
                    $expandibleTree.treeview('toggleNodeExpanded', [expandibleNodes, {
                        silent: $('#chk-expand-silent').is(':checked')
                    }]);
                });

                // Expand/collapse all
                $('#btn-expand-all').on('click', function(e) {
                    var levels = $('#select-expand-all-levels').val();
                    $expandibleTree.treeview('expandAll', {
                        levels: levels,
                        silent: $('#chk-expand-silent').is(':checked')
                    });
                });

                $('#btn-collapse-all').on('click', function(e) {
                    $expandibleTree.treeview('collapseAll', {
                        silent: $('#chk-expand-silent').is(':checked')
                    });
                });



                var $checkableTree = $('#treeview-checkable').treeview({
                    data: defaultData,
                    showIcon: false,
                    showCheckbox: true,
                    onNodeChecked: function(event, node) {
                        $('#checkable-output').prepend('<p>' + node.text + ' was checked</p>');
                    },
                    onNodeUnchecked: function(event, node) {
                        $('#checkable-output').prepend('<p>' + node.text + ' was unchecked</p>');
                    }
                });

                var findCheckableNodess = function() {
                    return $checkableTree.treeview('search', [$('#input-check-node').val(), {
                        ignoreCase: false,
                        exactMatch: false
                    }]);
                };
                var checkableNodes = findCheckableNodess();

                // Check/uncheck/toggle nodes
                $('#input-check-node').on('keyup', function(e) {
                    checkableNodes = findCheckableNodess();
                    $('.check-node').prop('disabled', !(checkableNodes.length >= 1));
                });

                $('#btn-check-node.check-node').on('click', function(e) {
                    $checkableTree.treeview('checkNode', [checkableNodes, {
                        silent: $('#chk-check-silent').is(':checked')
                    }]);
                });

                $('#btn-uncheck-node.check-node').on('click', function(e) {
                    $checkableTree.treeview('uncheckNode', [checkableNodes, {
                        silent: $('#chk-check-silent').is(':checked')
                    }]);
                });

                $('#btn-toggle-checked.check-node').on('click', function(e) {
                    $checkableTree.treeview('toggleNodeChecked', [checkableNodes, {
                        silent: $('#chk-check-silent').is(':checked')
                    }]);
                });

                // Check/uncheck all
                $('#btn-check-all').on('click', function(e) {
                    $checkableTree.treeview('checkAll', {
                        silent: $('#chk-check-silent').is(':checked')
                    });
                });

                $('#btn-uncheck-all').on('click', function(e) {
                    $checkableTree.treeview('uncheckAll', {
                        silent: $('#chk-check-silent').is(':checked')
                    });
                });



                var $disabledTree = $('#treeview-disabled').treeview({
                    data: defaultData,
                    onNodeDisabled: function(event, node) {
                        $('#disabled-output').prepend('<p>' + node.text + ' was disabled</p>');
                    },
                    onNodeEnabled: function(event, node) {
                        $('#disabled-output').prepend('<p>' + node.text + ' was enabled</p>');
                    },
                    onNodeCollapsed: function(event, node) {
                        $('#disabled-output').prepend('<p>' + node.text + ' was collapsed</p>');
                    },
                    onNodeUnchecked: function(event, node) {
                        $('#disabled-output').prepend('<p>' + node.text + ' was unchecked</p>');
                    },
                    onNodeUnselected: function(event, node) {
                        $('#disabled-output').prepend('<p>' + node.text + ' was unselected</p>');
                    }
                });

                var findDisabledNodes = function() {
                    return $disabledTree.treeview('search', [$('#input-disable-node').val(), {
                        ignoreCase: false,
                        exactMatch: false
                    }]);
                };
                var disabledNodes = findDisabledNodes();

                // Expand/collapse/toggle nodes
                $('#input-disable-node').on('keyup', function(e) {
                    disabledNodes = findDisabledNodes();
                    $('.disable-node').prop('disabled', !(disabledNodes.length >= 1));
                });

                $('#btn-disable-node.disable-node').on('click', function(e) {
                    $disabledTree.treeview('disableNode', [disabledNodes, {
                        silent: $('#chk-disable-silent').is(':checked')
                    }]);
                });

                $('#btn-enable-node.disable-node').on('click', function(e) {
                    $disabledTree.treeview('enableNode', [disabledNodes, {
                        silent: $('#chk-disable-silent').is(':checked')
                    }]);
                });

                $('#btn-toggle-disabled.disable-node').on('click', function(e) {
                    $disabledTree.treeview('toggleNodeDisabled', [disabledNodes, {
                        silent: $('#chk-disable-silent').is(':checked')
                    }]);
                });

                // Expand/collapse all
                $('#btn-disable-all').on('click', function(e) {
                    $disabledTree.treeview('disableAll', {
                        silent: $('#chk-disable-silent').is(':checked')
                    });
                });

                $('#btn-enable-all').on('click', function(e) {
                    $disabledTree.treeview('enableAll', {
                        silent: $('#chk-disable-silent').is(':checked')
                    });
                });



                var lifecycleTreeOptions = {
                    data: defaultData,
                    levels: 3,
                    onLoading: function(event) {
                        $('#lifecycle-output').prepend('<p>Loaded data</p>');
                    },
                    onInitialized: function(event, nodes) {
                        $('#lifecycle-output').prepend('<p>Initialized nodes</p>');
                    },
                    onNodeRendered: function(event, node) {
                        $('#lifecycle-output').prepend('<p>Finished rendering node : ' + node.text + '</p>');
                        if (node.text === 'Parent 1') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:darkred;">L</span>'));
                        } else if (node.text === 'Child 1') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:red;">I</span>'));
                        } else if (node.text === 'Grandchild 1') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:orange;">F</span>'));
                        } else if (node.text === 'Grandchild 2') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:gold;">E</span>'));
                        } else if (node.text === 'Child 2') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:green;">C</span>'));
                        } else if (node.text === 'Parent 2') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:blue;">Y</span>'));
                        } else if (node.text === 'Parent 3') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:indigo;">C</span>'));
                        } else if (node.text === 'Parent 4') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:violet;">L</span>'));
                        } else if (node.text === 'Parent 5') {
                            node.$el.append($('<span class="badge" style="width:24px;background-color:darkviolet;">E</span>'));
                        }
                    },
                    onRendered: function(event, nodes) {
                        $('#lifecycle-output').prepend('<p>Finished rendering</p>');
                    },
                    onDestroyed: function(event) {
                        $('#lifecycle-output').prepend('<p>Tree destroyed</p>');
                    }
                };
                var $lifecycleTree = $('#treeview-lifecycle').treeview(lifecycleTreeOptions);

                $('#btn-init').on('click', function(e) {
                    $('#btn-init').prop('disabled', true);
                    $('#btn-remove').prop('disabled', false);
                    $lifecycleTree.treeview('init', lifecycleTreeOptions);
                });

                $('#btn-remove').on('click', function(e) {
                    $('#btn-remove').prop('disabled', true);
                    $('#btn-init').prop('disabled', false);
                    $lifecycleTree.treeview('remove');
                });



                var $jsonTree = $('#treeview-json').treeview({
                    data: json
                });

                var $ajaxTree = $('#treeview-ajax').treeview({
                    dataUrl: {
                        url: './data.json'
                    }
                });
            });
        </script>
    @endslot
</x-app-layout>
