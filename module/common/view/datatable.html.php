<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
if($config->debug)
{
    css::import($jsRoot . 'datatable/min.css');
    js::import($jsRoot . 'datatable/min.js');
}
?>
<style>
.table-datatable tbody > tr td,
.table-datatable thead > tr th {max-height: 34px; line-height: 21px;}
.table-datatable tbody > tr td .btn-icon > i {line-height: 19px;}
.hide-side .table-datatable thead > tr > th.check-btn i {visibility: hidden;}
.hide-side .side-handle {line-height: 33px}
.table-datatable .checkbox-row {display: none}
.outer .datatable {border: 1px solid #ddd;}
.outer .datatable .table, .outer .datatable .table tfoot td {border: none;}
.datatable .table>tbody>tr>td.col-hover, .datatable .table>tbody>tr.hover>td {background-color: #ebf2f9 !important;}
.datatable-span.flexarea .scroll-slide {bottom: -30px}
</style>
<script> 
$(document).ready(function()
{
    var $datatables = $('table.datatable');
    $datatables.find('tbody input:checkbox').addClass('checkbox-row');
    $datatables.datatable(
    {
        customizable  : false, 
        sortable      : false,
        tableClass    : 'tablesorter',
        checksChanged : function(event)
        {
            var checks = event.checks.checks;
            this.$datatable.find('.checkbox-row').each(function()
            {
                var $box = $(this);
                var check = false, val = $box.val();
                for(var i = 0; i < checks.length; i++)
                {
                    if(checks[i] == val) check = true;
                }
                $box.prop('checked', check);
            });
        },
        footer: 'test'
    });
    $datatables.find('.checkbox-row').remove();
});
</script>
