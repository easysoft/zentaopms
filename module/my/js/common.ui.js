$(function()
{
    if(typeof mode === 'string')
    {
        $('#mainNavbar li[data-id=' + mode + ']').addClass('active');
        if(typeof rawMethod === 'string' && rawMethod == 'work')
        {
            $("#mainNavbar li.nav-item a[data-id='task']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.task + '</span>');
            $("#mainNavbar li.nav-item a[data-id='story']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.story + '</span>');
            $("#mainNavbar li.nav-item a[data-id='bug']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.bug + '</span>');
            $("#mainNavbar li.nav-item a[data-id='testcase']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.case + '</span>');
            $("#mainNavbar li.nav-item a[data-id='testtask']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.testtask + '</span>');

            if(isOpenedURAndSR !== 0) $("#mainNavbar li.nav-item a[data-id='requirement']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.requirement + '</span>');

            if(isBiz !== 0 || isMax !== 0) 
            {
                $("#mainNavbar li.nav-item a[data-id='feedback']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.feeckback + '</span>');
                $("#mainNavbar li.nav-item a[data-id='ticket']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.ticket + '</span>');
            }
            if(isMax !== 0)
            {
                $("#mainNavbar li.nav-item a[data-id='issue']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.issue + '</span>');
                $("#mainNavbar li.nav-item a[data-id='risk']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.risk + '</span>');
                $("#mainNavbar li.nav-item a[data-id='nc']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.qa + '</span>');
                $("#mainNavbar li.nav-item a[data-id='myMeeting']").append('<span class="label rounded-2xl gray-pale h-4">' + todoCount.meeting + '</span>');
            }
        }
    }
});
