@extends('layouts.app')

@section('content')


<div class="container-fluid"> 

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h3 class="page-title fw-semibold fs-18">Organization</h3>
        <div class="ms-md-1 ms-0">
            <nav>
                @can('organizations create')
                    <div class="d-flex">
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light" id="create"><i class="ri-add-line fw-semibold align-middle me-1"></i> Create New</a>                  
                    </div>
                @endcan
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Start::row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">

<ul id="browser" class="filetree">
    <li><span class="folder">Folder 1</span>
        <ul>
            <li><span class="file">Item 1.1</span></li>
        </ul>
    </li>
    <li><span class="folder">Folder 2</span>
        <ul>
            <li><span class="folder">Subfolder 2.1</span>
                <ul id="folder21">
                    <li><span class="file">File 2.1.1</span></li>
                    <li><span class="file">File 2.1.2</span></li>
                </ul>
            </li>
            <li><span class="file">File 2.2</span></li>
        </ul>
    </li>
    <li class="closed"><span class="folder">Folder 3 (closed at start)</span>
        <ul>
            <li><span class="file">File 3.1</span></li>
        </ul>
    </li>
    <li><span class="file">File 4</span></li>
</ul>
</div>
</div>
</div>
<!--End::row -->

</div>

@endsection
@section('scripts')
<script src="//code.jquery.com/jquery.min.js"></script>
<script src={{ asset('hyper/dist/saas/assets/vendor/treeview/jquery.treeview.js') }}></script>
<script src={{ asset('hyper/dist/saas/assets/vendor/treeview/jquery.cookie.js') }}></script>
<script src={{ asset('hyper/dist/saas/assets/vendor/treeview/demo.js') }}></script>
<script>
   $(function() {

var defaultData = [
  {
    text: 'Parent 1',
    href: '#parent1',
    tags: ['4'],
    nodes: [
      {
        text: 'Child 1',
        href: '#child1',
        tags: ['2'],
        nodes: [
          {
            text: 'Grandchild 1',
            href: '#grandchild1',
            tags: ['0']
          },
          {
            text: 'Grandchild 2',
            href: '#grandchild2',
            tags: ['0']
          }
        ]
      },
      {
        text: 'Child 2',
        href: '#child2',
        tags: ['0']
      }
    ]
  },
  {
    text: 'Parent 2',
    href: '#parent2',
    tags: ['0']
  },
  {
    text: 'Parent 3',
    href: '#parent3',
     tags: ['0']
  },
  {
    text: 'Parent 4',
    href: '#parent4',
    tags: ['0']
  },
  {
    text: 'Parent 5',
    href: '#parent5'  ,
    tags: ['0']
  }
];

var alternateData = [
  {
    text: 'Parent 1',
    tags: ['2'],
    nodes: [
      {
        text: 'Child 1',
        tags: ['3'],
        nodes: [
          {
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
    href: '#demo',
    tags: ['11']
  },
  {
    text: 'Parent 4',
    icon: 'glyphicon glyphicon-cloud-download',
    href: '/demo.html',
    tags: ['19'],
    selected: true
  },
  {
    text: 'Parent 5',
    icon: 'glyphicon glyphicon-certificate',
    color: 'pink',
    backColor: 'red',
    href: 'http://www.tesco.com',
    tags: ['available','0']
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

$('#treeview10').treeview({
  color: "#428bca",
  enableLinks: true,
  data: defaultData
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
  var results = $searchableTree.treeview('search', [ pattern, options ]);

  var output = '<p>' + results.length + ' matches found</p>';
  $.each(results, function (index, result) {
    output += '<p>- ' + result.text + '</p>';
  });
  $('#search-output').html(output);
}

$('#btn-search').on('click', search);
$('#input-search').on('keyup', search);

$('#btn-clear-search').on('click', function (e) {
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
    onNodeUnselected: function (event, node) {
      $('#selectable-output').prepend('<p>' + node.text + ' was unselected</p>');
    }
  });
};
var $selectableTree = initSelectableTree();

var findSelectableNodes = function() {
  return $selectableTree.treeview('search', [ $('#input-select-node').val(), { ignoreCase: false, exactMatch: false } ]);
};
var selectableNodes = findSelectableNodes();

$('#chk-select-multi:checkbox').on('change', function () {
  console.log('multi-select change');
  $selectableTree = initSelectableTree();
  selectableNodes = findSelectableNodes();          
});

// Select/unselect/toggle nodes
$('#input-select-node').on('keyup', function (e) {
  selectableNodes = findSelectableNodes();
  $('.select-node').prop('disabled', !(selectableNodes.length >= 1));
});

$('#btn-select-node.select-node').on('click', function (e) {
  $selectableTree.treeview('selectNode', [ selectableNodes, { silent: $('#chk-select-silent').is(':checked') }]);
});

$('#btn-unselect-node.select-node').on('click', function (e) {
  $selectableTree.treeview('unselectNode', [ selectableNodes, { silent: $('#chk-select-silent').is(':checked') }]);
});

$('#btn-toggle-selected.select-node').on('click', function (e) {
  $selectableTree.treeview('toggleNodeSelected', [ selectableNodes, { silent: $('#chk-select-silent').is(':checked') }]);
});



var $expandibleTree = $('#treeview-expandible').treeview({
  data: defaultData,
  onNodeCollapsed: function(event, node) {
    $('#expandible-output').prepend('<p>' + node.text + ' was collapsed</p>');
  },
  onNodeExpanded: function (event, node) {
    $('#expandible-output').prepend('<p>' + node.text + ' was expanded</p>');
  }
});

var findExpandibleNodess = function() {
  return $expandibleTree.treeview('search', [ $('#input-expand-node').val(), { ignoreCase: false, exactMatch: false } ]);
};
var expandibleNodes = findExpandibleNodess();

// Expand/collapse/toggle nodes
$('#input-expand-node').on('keyup', function (e) {
  expandibleNodes = findExpandibleNodess();
  $('.expand-node').prop('disabled', !(expandibleNodes.length >= 1));
});

$('#btn-expand-node.expand-node').on('click', function (e) {
  var levels = $('#select-expand-node-levels').val();
  $expandibleTree.treeview('expandNode', [ expandibleNodes, { levels: levels, silent: $('#chk-expand-silent').is(':checked') }]);
});

$('#btn-collapse-node.expand-node').on('click', function (e) {
  $expandibleTree.treeview('collapseNode', [ expandibleNodes, { silent: $('#chk-expand-silent').is(':checked') }]);
});

$('#btn-toggle-expanded.expand-node').on('click', function (e) {
  $expandibleTree.treeview('toggleNodeExpanded', [ expandibleNodes, { silent: $('#chk-expand-silent').is(':checked') }]);
});

// Expand/collapse all
$('#btn-expand-all').on('click', function (e) {
  var levels = $('#select-expand-all-levels').val();
  $expandibleTree.treeview('expandAll', { levels: levels, silent: $('#chk-expand-silent').is(':checked') });
});

$('#btn-collapse-all').on('click', function (e) {
  $expandibleTree.treeview('collapseAll', { silent: $('#chk-expand-silent').is(':checked') });
});



var $checkableTree = $('#treeview-checkable').treeview({
  data: defaultData,
  showIcon: false,
  showCheckbox: true,
  onNodeChecked: function(event, node) {
    $('#checkable-output').prepend('<p>' + node.text + ' was checked</p>');
  },
  onNodeUnchecked: function (event, node) {
    $('#checkable-output').prepend('<p>' + node.text + ' was unchecked</p>');
  }
});

var findCheckableNodess = function() {
  return $checkableTree.treeview('search', [ $('#input-check-node').val(), { ignoreCase: false, exactMatch: false } ]);
};
var checkableNodes = findCheckableNodess();

// Check/uncheck/toggle nodes
$('#input-check-node').on('keyup', function (e) {
  checkableNodes = findCheckableNodess();
  $('.check-node').prop('disabled', !(checkableNodes.length >= 1));
});

$('#btn-check-node.check-node').on('click', function (e) {
  $checkableTree.treeview('checkNode', [ checkableNodes, { silent: $('#chk-check-silent').is(':checked') }]);
});

$('#btn-uncheck-node.check-node').on('click', function (e) {
  $checkableTree.treeview('uncheckNode', [ checkableNodes, { silent: $('#chk-check-silent').is(':checked') }]);
});

$('#btn-toggle-checked.check-node').on('click', function (e) {
  $checkableTree.treeview('toggleNodeChecked', [ checkableNodes, { silent: $('#chk-check-silent').is(':checked') }]);
});

// Check/uncheck all
$('#btn-check-all').on('click', function (e) {
  $checkableTree.treeview('checkAll', { silent: $('#chk-check-silent').is(':checked') });
});

$('#btn-uncheck-all').on('click', function (e) {
  $checkableTree.treeview('uncheckAll', { silent: $('#chk-check-silent').is(':checked') });
});



var $disabledTree = $('#treeview-disabled').treeview({
  data: defaultData,
  onNodeDisabled: function(event, node) {
    $('#disabled-output').prepend('<p>' + node.text + ' was disabled</p>');
  },
  onNodeEnabled: function (event, node) {
    $('#disabled-output').prepend('<p>' + node.text + ' was enabled</p>');
  },
  onNodeCollapsed: function(event, node) {
    $('#disabled-output').prepend('<p>' + node.text + ' was collapsed</p>');
  },
  onNodeUnchecked: function (event, node) {
    $('#disabled-output').prepend('<p>' + node.text + ' was unchecked</p>');
  },
  onNodeUnselected: function (event, node) {
    $('#disabled-output').prepend('<p>' + node.text + ' was unselected</p>');
  }
});

var findDisabledNodes = function() {
  return $disabledTree.treeview('search', [ $('#input-disable-node').val(), { ignoreCase: false, exactMatch: false } ]);
};
var disabledNodes = findDisabledNodes();

// Expand/collapse/toggle nodes
$('#input-disable-node').on('keyup', function (e) {
  disabledNodes = findDisabledNodes();
  $('.disable-node').prop('disabled', !(disabledNodes.length >= 1));
});

$('#btn-disable-node.disable-node').on('click', function (e) {
  $disabledTree.treeview('disableNode', [ disabledNodes, { silent: $('#chk-disable-silent').is(':checked') }]);
});

$('#btn-enable-node.disable-node').on('click', function (e) {
  $disabledTree.treeview('enableNode', [ disabledNodes, { silent: $('#chk-disable-silent').is(':checked') }]);
});

$('#btn-toggle-disabled.disable-node').on('click', function (e) {
  $disabledTree.treeview('toggleNodeDisabled', [ disabledNodes, { silent: $('#chk-disable-silent').is(':checked') }]);
});

// Expand/collapse all
$('#btn-disable-all').on('click', function (e) {
  $disabledTree.treeview('disableAll', { silent: $('#chk-disable-silent').is(':checked') });
});

$('#btn-enable-all').on('click', function (e) {
  $disabledTree.treeview('enableAll', { silent: $('#chk-disable-silent').is(':checked') });
});



var $tree = $('#treeview12').treeview({
  data: json
});
  });
</script>
@endsection