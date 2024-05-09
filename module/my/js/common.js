$(function()
{
    if(typeof mode === 'string')
    {
        $('#subNavbar li[data-id=' + mode + ']').addClass('active');
        if(typeof rawMethod === 'string' && rawMethod == 'work')
        {
            $("#subNavbar li[data-id='task'] a").append('<span class="label label-light label-badge">' + todoCount.task + '</span>');
            $("#subNavbar li[data-id='story'] a").append('<span class="label label-light label-badge">' + todoCount.story + '</span>');
            $("#subNavbar li[data-id='bug'] a").append('<span class="label label-light label-badge">' + todoCount.bug + '</span>');
            $("#subNavbar li[data-id='testcase'] a").append('<span class="label label-light label-badge">' + todoCount.case + '</span>');
            $("#subNavbar li[data-id='testtask'] a").append('<span class="label label-light label-badge">' + todoCount.testtask + '</span>');

            if(isOpenedURAndSR !== 0) $("#subNavbar li[data-id='requirement'] a").append('<span class="label label-light label-badge">' + todoCount.requirement + '</span>');

            if(isBiz !== 0 || isMax !== 0) 
            {
                $("#subNavbar li[data-id='feedback'] a").append('<span class="label label-light label-badge">' + todoCount.feedback + '</span>');
                $("#subNavbar li[data-id='ticket'] a").append('<span class="label label-light label-badge">' + todoCount.ticket + '</span>');
            }
            if(isMax !== 0)
            {
                $("#subNavbar li[data-id='issue'] a").append('<span class="label label-light label-badge">' + todoCount.issue + '</span>');
                $("#subNavbar li[data-id='risk'] a").append('<span class="label label-light label-badge">' + todoCount.risk + '</span>');
                $("#subNavbar li[data-id='nc'] a").append('<span class="label label-light label-badge">' + todoCount.qa + '</span>');
                $("#subNavbar li[data-id='myMeeting'] a").append('<span class="label label-light label-badge">' + todoCount.meeting + '</span>');
            }
        }
    }

    var $scp = $('[data-id="changePassword"] a');
    if($scp.length > 0)
    {
        var sign = config.requestType == 'GET' ? '&' : '?';
        $scp.attr('href', $scp.attr('href') + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});
    }
});
