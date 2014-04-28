$(function() 
{
    var sp = $('#submenuprofile'), scp = $('#submenuchangePassword');
    sp.attr('href', sp.attr('href') + '?onlybody=yes').modalTrigger({width:600, type:'iframe'});
    scp.attr('href', scp.attr('href') + '?onlybody=yes').modalTrigger({width:500, type:'iframe'});
});
