$(document).ready(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();
    if($('#caseList thead th.w-title').width() < 150) $('#caseList thead th.w-title').width(150);
});
