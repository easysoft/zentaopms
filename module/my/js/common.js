$(function() 
{
    var sp = $('#submenuprofile'), scp = $('#submenuchangePassword');
    var sign = config.requestType == 'GET' ? '&' : '?';
    sp.attr('href', sp.attr('href')   + sign + 'onlybody=yes').modalTrigger({width:600, type:'iframe'});
    scp.attr('href', scp.attr('href') + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});
});
