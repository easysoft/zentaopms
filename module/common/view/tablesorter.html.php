<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
js::import($jsRoot . 'jquery/tablesorter/min.js');
js::import($jsRoot . 'jquery/tablesorter/metadata.js');
?>
<style>
table.tablesorter tr.tablesorter-headerRow .header.tablesorter-headerUnSorted .tablesorter-header-inner:after{font-family: NewZenIcon; font-weight: normal; content: "\e6bd"; font-size: 14px;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortUp .tablesorter-header-inner:after{font-family: NewZenIcon; font-weight: normal; content: "\e6b9"; font-size: 14px; color: #03C;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortDown .tablesorter-header-inner:after{font-family: NewZenIcon; font-weight: normal; content: "\e6b8"; font-size: 14px; color: #03C;}
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
