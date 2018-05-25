<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
js::import($jsRoot . 'jquery/tablesorter/min.js');
js::import($jsRoot . 'jquery/tablesorter/metadata.js');
?>
<style>
.tablesorter-header-inner {cursor: pointer;}
.tablesorter-header-inner:hover {color: #006af1;}
table.tablesorter tr.tablesorter-headerRow .header.tablesorter-headerUnSorted .tablesorter-header-inner:after {font-family: ZentaoIcon; font-weight: normal; content: "\f0dc"; font-size: 14px;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortUp .tablesorter-header-inner:after{font-family: ZentaoIcon; font-weight: normal; content: "\f0d8"; color: #006af1;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortDown .tablesorter-header-inner:after{font-family: ZentaoIcon; font-weight: normal; content: "\f0d7"; color: #006af1;}
table.tablesorter tr.tablesorter-headerRow .header.sorter-false .tablesorter-header-inner:after{content:"";}
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
