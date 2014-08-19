<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php 
js::import($jsRoot . 'jquery/tablesorter/min.js');
js::import($jsRoot . 'jquery/tablesorter/metadata.js');
?>
<script language='javascript'>

/* sort table after page load. */
$(function() { sortTable(); } );

function sortTable()
{
    $('.tablesorter').tablesorter(
    {
        saveSort: true,
        widgets: ['zebra', 'saveSort'], 
        widgetZebra: {css: ['odd', 'even'] }
    });

    $('.tablesorter tbody tr').hover(
        function(){$(this).addClass('hoover')},
        function(){$(this).removeClass('hoover')}
    );

    $('.tablesorter tbody tr').click(
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
