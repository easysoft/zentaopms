<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php js::import($jsRoot . 'datatable/min.js'); ?>
<?php css::import($jsRoot . 'datatable/min.css'); ?>
<?php if(!empty($lang->datatable)):?>
<style>
.datatable .table>tbody>tr.active>td.col-hover, .datatable .table>tbody>tr.active.hover>td {background-color: #f3eed8 !important;}
.datatable {margin-bottom: 0;}
.datatable .table>tbody>tr>td, .datatable .table>thead>tr>th {line-height: 30px; padding-top: 3px; padding-bottom: 3px; height: 40px; vertical-align: middle; white-space: nowrap;}
.datatable .table>tbody>tr.hover {box-shadow: none!important;}
.datatable .flexarea .table-children,
.datatable .fixed-left .table-children {border-right: none;}
.datatable .flexarea .table-children,
.datatable .fixed-right .table-children {border-left: none;}
.datatable tr.hover td.c-actions .more {display: block; background-color: #ebf2f9}
.datatable .fixed-left .table,
.datatable .flexarea .table,
.datatable .flexarea tbody > tr.checked,
.datatable .fixed-left tbody > tr.checked {border-top-right-radius: 0; border-bottom-right-radius: 0;}
.datatable .flexarea tbody > tr.checked,
.datatable .fixed-right .table,
.datatable .flexarea .table,
.datatable .fixed-right tbody > tr.checked {border-top-left-radius: 0; border-bottom-left-radius: 0;}
.datatable .flexarea tbody>tr.checked>td:first-child:before,
.datatable .fixed-right tbody>tr.checked>td:first-child:before {display: none}
.datatable>.scroll-wrapper {z-index: 10;}
.has-fixed-footer .scroll-wrapper {bottom: 97px; position: fixed;}
.datatable .flexarea thead>tr>th:first-child,
.datatable .flexarea tbody>tr>td:first-child,
.datatable .fixed-right thead>tr>th:first-child,
.datatable .fixed-right tbody>tr>td {padding-left: 5px!important;}
.datatable .c-actions {white-space: nowrap;}
.datatable.head-fixed {padding-top: 41px;}
.datatable .table>thead>tr>th.col-hover {background: rgba(0,0,0,.075);}
</style>
<script> 
<?php $datatableId = $this->moduleName . ucfirst($this->methodName);?>
$(document).ready(function()
{
    'use strict';

    var $datatable  = $('table.datatable').first();
    var datatableId = $datatable.attr('id');
    var dtSetting   = $.cookie('datatable.<?php echo $datatableId?>' + '.cols') || {};
    if(dtSetting === 'null') dtSetting = {};
    if(typeof dtSetting === 'string') dtSetting = $.parseJSON(dtSetting);
    var $modal = $('#customDatatable');
    var $checkList = $modal.find('.modal-body > .table > tbody');

    $datatable.find('thead>tr>th').each(function(idx)
    {
        var $th = $(this);
        idx = $th.data('index') || idx;
        var colSetting = dtSetting[idx];
        $th.toggleClass('ignore', !!(colSetting && colSetting.ignore));
    });

    $checkList.on('click', 'tr', function()
    {
        var $tr = $(this);
        if($tr.hasClass('disabled')) return;
        $tr.toggleClass('checked');
    });

    $datatable.datatable(
    {
        customizable  : false, 
        sortable      : false,
        scrollPos     : 'out',
        tableClass    : 'tablesorter',
        storage       : false,
        fixCellHeight : false,
        selectable     : false,
        fixedHeader: true,
        ready: function()
        {
            $datatable.addClass('datatable-origin');
            if ($datatable.hasClass('has-sort-head'))
            {
                this.$datatable.find('.table').addClass('has-sort-head');
            }
            this.$datatable.find('.sparkline').sparkline();
        }
    });

    $(window).on('fixFooter', function(e, isFixed)
    {
        $('body').toggleClass('has-fixed-footer', isFixed);
    });

    window.saveDatatableConfig = function(name, value, reload, global)
    {
        if('<?php echo $this->app->user->account?>' == 'guest') return;
        var datatableId = '<?php echo $datatableId;?>';
        if(typeof value === 'object') value = JSON.stringify(value);
        if(typeof global === 'undefined') global = 0;
        $.ajax(
        {
            type: "POST",
            dataType: 'json',
            data: {target: datatableId, name: name, value: value, global: global},
            success:function(e){if(reload) window.location.reload();},
            url: '<?php echo $this->createLink('datatable', 'ajaxSave')?>'
        });
    };
});
</script>
<?php endif;?>

