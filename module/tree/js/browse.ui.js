window.toggleCopy = function(toggle)
{
   $('.form-group.copy').toggleClass('hidden', toggle);
}

$(document).ready(function()
{
    toggleCopy(true);
});

window.syncProductOrProject = function(obj, type)
{
    if(type == 'product') viewType = 'story';
    if(type == 'project') viewType = 'task';

    const rootID = $(obj).find('input.pick-value').val();
    const link   = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + rootID + "&viewType=" + viewType + "&branch=all&rootModuleID=0&returnType=json");
}
