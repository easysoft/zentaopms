<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
js::import($jsRoot . 'jquery/tablesorter/min.js');
js::import($jsRoot . 'jquery/tablesorter/metadata.js');
?>
<style>
table.tablesorter tr.tablesorter-headerRow .header.tablesorter-headerUnSorted .tablesorter-header-inner:after{font-family: ZenIcon; font-weight: normal; content: " \e6bd"; font-size: 14px;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortUp .tablesorter-header-inner:after{font-family: ZenIcon; font-weight: normal; content: " \e6b9"; font-size: 14px; color: #03C;}
table.tablesorter tr.tablesorter-headerRow .header.headerSortDown .tablesorter-header-inner:after{font-family: ZenIcon; font-weight: normal; content: " \e6b8"; font-size: 14px; color: #03C;}
table.tablesorter tr.tablesorter-headerRow .header.sorter-false .tablesorter-header-inner:after{content:"";}
</style>
<script language='javascript'>

/* sort table after page load. */
$(function(){sortTable('.tablesorter');});

function sortTable(obj)
{
    if(typeof(obj) == 'undefined') obj = '.tablesorter';
    $(obj).tablesorter(
    {
        saveSort: true,
        widgets: ['zebra', 'saveSort'], 
        widgetZebra: {css: ['odd', 'even'] }
    });

    $(obj + ' tbody tr').hover(
        function(){$(this).addClass('hoover')},
        function(){$(this).removeClass('hoover')}
    );

    $(obj + ' tbody tr').click(
        function()
        {
            if($(this).attr('class').indexOf('clicked') > 0)
            {
                $(this).removeClass('clicked');
            }
            else
            {
                $(this).addClass('clicked');
            }
        }
    );
}
</script>
