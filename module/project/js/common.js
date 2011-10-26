function setWhite(acl)
{
    acl == 'custom' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}
function switchGroup(projectID, groupBy)
{
    link = createLink('project', 'groupTask', 'project=' + projectID + '&groupBy=' + groupBy);
    location.href=link;
}

$(document).ready(function()
{
    if($('#submenuall').size()) $("#submenuall").colorbox({width:1000, height:600, iframe:true, transition:'elastic', speed:350, scrolling:true});
})
