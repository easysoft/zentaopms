$(function()
{
    if(typeof mode === 'string')
    {
        $('#subNavbar li[data-id=' + mode + ']').addClass('active');
        if(typeof rawMethod === 'string' && rawMethod == 'work') $('#subNavbar li[data-id=' + mode + '] a').append('<span class="label label-light label-badge">' + total + '</span>');
    }
    var scp  = $('[data-id="changePassword"] a');
    var link = scp.attr('href');
    var sign = link.indexOf('?') >= 0 ? '&' : '?';
    scp.attr('href', link + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});
});
