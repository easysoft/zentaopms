$(function() 
{
    if(typeof(mode) != 'undefined') 
    {
        if(mode == 'testcase') mode = 'testtask';
        if(mode == 'requirement') mode = 'story';
        $('#subNavbar li[data-id=' + mode + ']').addClass('active');
    }
    var scp  = $('[data-id="changePassword"] a');
    var sign = config.requestType == 'GET' ? '&' : '?';
    scp.attr('href', scp.attr('href') + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});
});
