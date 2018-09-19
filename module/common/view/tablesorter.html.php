<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
js::import($jsRoot . 'jquery/tablesorter/min.js');
js::import($jsRoot . 'jquery/tablesorter/metadata.js');
?>
<style>
.tablesorter-header-inner {cursor: pointer;}
.tablesorter-header-inner:hover {color: #000;font-weight:bold;}
table.tablesorter tr.tablesorter-headerRow .header.tablesorter-headerUnSorted .tablesorter-header-inner:after {font-family: ZentaoIcon; font-weight: normal; content: "\f0dc"; font-size: 14px; color: #838a9c}
table.tablesorter tr.tablesorter-headerRow .header.headerSortUp .tablesorter-header-inner{color: #000;font-weight:bold;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortUp .tablesorter-header-inner:after{font-family: ZentaoIcon; font-weight: normal; content: "\f0d8"; color: #000;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortDown .tablesorter-header-inner{color: #000;font-weight:bold;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortDown .tablesorter-header-inner:after{font-family: ZentaoIcon; font-weight: normal; content: "\f0d7"; color: #000;}
table.tablesorter tr.tablesorter-headerRow .header.sorter-false .tablesorter-header-inner:after{content:"";}
table.tablesorter.table-borderless > thead > tr > th {border-bottom: 1px solid #e5e5e5;}
</style>
<script>
function sortTable(selector, options)
{
    var $table = $(selector);
    $table.tablesorter($.extend(
    {
        saveSort: true,
        widgets: ['zebra', 'saveSort'],
        widgetZebra: {css: ['odd', 'even'] }
    }, $table.data(), options)).on('mouseenter', 'tbody tr', function()
    {
        $(this).addClass('hoover');
    }).on('mouseleave', 'tbody tr', function()
    {
        $(this).removeClass('hoover');
    }).on('click', 'tbody tr', function()
    {
        $(this).toggleClass('clicked');
    });
}
$.fn.sortTable = function(options)
{
    return this.each(function()
    {
        sortTable(this, options);
    });
};
/* sort table after page load. */
$(function(){$('.tablesorter').sortTable();});
</script>
