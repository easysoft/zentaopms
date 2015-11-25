$(function()
{
    $('#' + browseType + 'Tab').addClass('active');
    ajaxGetSearchForm();
    setTimeout(function(){fixedTfootAction('#importBugForm')}, 500);
});
