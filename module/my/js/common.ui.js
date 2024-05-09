window.updateMainNavbarBadges = function(options)
{
    const rawMethod       = options.rawMethod;
    const mode            = options.mode;
    const isIPD           = options.isIPD;
    const isMax           = options.isMax;
    const isBiz           = options.isBiz;
    const isOpenedURAndSR = options.isOpenedURAndSR;
    const todoCount       = options.todoCount;

    if(typeof mode !== 'string') return;

    const $mainNavbar = $('#mainNavbar');
    $mainNavbar.find('.nav-item > a[data-id=' + mode + ']').addClass('active');
    if(rawMethod == 'work')
    {
        const countMap = {task: 'task', story: 'story', bug: 'bug', testcase: 'case', testtask: 'testtask'};
        if(isOpenedURAndSR !== 0)      $.extend(countMap, {requirement: 'requirement'});
        if(isBiz !== 0 || isMax !== 0 || isIPD !== 0) $.extend(countMap, {feedback: 'feedback', ticket: 'ticket'});
        if(isMax !== 0 || isIPD !== 0)                $.extend(countMap, {issue: 'issue', risk: 'risk', nc: 'qa', myMeeting: 'meeting'});
        if(isIPD !== 0)                               $.extend(countMap, {demand: 'demand'});

        $.each(countMap, (name, key) =>
        {
            const $item = $mainNavbar.find('.nav-item > a[data-id=' + name + ']');
            const $label = $item.find('.label');
            if($label.length) $label.text(todoCount[key]);
            else $item.append('<span class="label rounded gray-pale size-sm">' + todoCount[key] + '</span>');
        });
    }
};
