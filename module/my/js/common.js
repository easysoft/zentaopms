$(function() 
{
    var scp  = $('[data-id="changePassword"] a');
    var sign = config.requestType == 'GET' ? '&' : '?';
    scp.attr('href', scp.attr('href') + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});
});
