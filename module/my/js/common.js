$(function() 
{
    var sp = $('[data-id="profile"] a'), scp = $('[data-id="changePassword"] a');
    var sign = config.requestType == 'GET' ? '&' : '?';
    sp.attr('href', sp.attr('href')   + sign + 'onlybody=yes').modalTrigger({width:600, type:'iframe'});
    scp.attr('href', scp.attr('href') + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});

    /* Fixed table actions */
    if($('table.tablesorter').closest('form').size() > 0) fixedTfootAction($('table.tablesorter').closest('form'));
    /* Fixed table header */
    if($('table.tablesorter').size() > 0) fixedTheadOfList($('table.tablesorter:first'));

    $('.dropdown-menu .with-search .menu-search').click(function(e)
    {
        e.stopPropagation();
        return false;
    }).on('keyup change paste', 'input', function()
    {
        var val = $(this).val().toLowerCase();
        var $options = $(this).closest('.dropdown-menu.with-search').find('.option');
        if(val == '') return $options.removeClass('hide');
        $options.each(function()
        {
            var $option = $(this);
            $option.toggleClass('hide', $option.text().toString().toLowerCase().indexOf(val) < 0 && $option.data('key').toString().toLowerCase().indexOf(val) < 0);
        });
    });
});
