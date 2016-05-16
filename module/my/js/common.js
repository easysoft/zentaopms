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
});
