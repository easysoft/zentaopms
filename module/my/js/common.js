$(function() 
{
    var sp = $('#submenuprofile'), scp = $('#submenuchangePassword');
    var sign = config.requestType == 'GET' ? '&' : '?';
    sp.attr('href', sp.attr('href')   + sign + 'onlybody=yes').modalTrigger({width:600, type:'iframe'});
    scp.attr('href', scp.attr('href') + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});

    /* Fix table actions */
    if($('table.tablesorter').closest('form').size() > 0) fixedTfootAction($('table.tablesorter').closest('form'));
});
