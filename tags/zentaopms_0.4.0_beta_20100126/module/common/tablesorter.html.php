<link rel='stylesheet' href='<?php echo $clientTheme;?>tablesorter.css' />
<script src='<?php echo $jsRoot;?>jquery/tablesorter/min.js' type='text/javascript'></script>
<script language='javascript'>
$(function() {

    $('.tablesorter').tablesorter(
        {
            widgets: ['zebra'], 
            widgetZebra: {css: ['odd', 'even'] }
        }
    ); 
    $('.tablesorter tr').hover(
        function(){$(this).addClass('hoover')},
        function(){$(this).removeClass('hoover')}
    );
    $('.tablesorter tr').click(
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
});
</script>
