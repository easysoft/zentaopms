$(function()
{
    if(typeof mode === 'string')
    {
        const $mainNavbar = $('#mainNavbar');
        $mainNavbar.find('.nav-item > a[data-id=' + mode + ']').addClass('active');
        if(typeof rawMethod === 'string' && rawMethod == 'work')
        {
            if($mainNavbar.find('.nav-item > a[data-id=task] .label').length) return false;

            const countMap = {task: 'task', story: 'story', bug: 'bug', testcase: 'case', testtask: 'testtask'};
            if(isOpenedURAndSR !== 0)      $.extend(countMap, {requirement: 'requirement'});
            if(isBiz !== 0 || isMax !== 0) $.extend(countMap, {feedback: 'feedback', ticket: 'ticket'});
            if(isMax !== 0)                $.extend(countMap, {issue: 'issue', risk: 'risk', nc: 'qa', myMeeting: 'meeting'});

            $.each(countMap, (name, key) => $mainNavbar.find('.nav-item > a[data-id=' + name + ']').append('<span class="label rounded gray-pale size-sm">' + todoCount[key] + '</span>'));
        }
    }
});
