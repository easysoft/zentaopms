$(function()
{
    if(typeof mode === 'string')
    {
        $('#subNavbar li[data-id=' + mode + ']').addClass('active');

        $("#subNavbar li[data-id='task'] a").append('<span class="label label-light label-badge">' + tasksCount + '</span>');
        $("#subNavbar li[data-id='requirement'] a").append('<span class="label label-light label-badge">' + reuqirementsCount + '</span>');
        $("#subNavbar li[data-id='story'] a").append('<span class="label label-light label-badge">' + storiesCount + '</span>');
        $("#subNavbar li[data-id='bug'] a").append('<span class="label label-light label-badge">' + bugsCount + '</span>');
        $("#subNavbar li[data-id='testcase'] a").append('<span class="label label-light label-badge">' + casesCount + '</span>');
        $("#subNavbar li[data-id='testtask'] a").append('<span class="label label-light label-badge">' + testTasksCount + '</span>');
    }
    var $scp  = $('[data-id="changePassword"] a');
    if($scp.length > 0)
    {
        var sign = config.requestType == 'GET' ? '&' : '?';
        $scp.attr('href', $scp.attr('href') + sign + 'onlybody=yes').modalTrigger({width:500, type:'iframe'});
    }
});
