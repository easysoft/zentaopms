function showLink(buildID, type, param)
{
    var method = type == 'story' ? 'linkStory' : 'linkBug';
    $.get(createLink('build', method, 'buildID=' + buildID + (typeof(param) == 'undefined' ? '' : param)), function(data)
    {
        var obj = type == 'story' ? '.tab-pane#stories .linkBox' : '.tab-pane#bugs .linkBox';
        $(obj).html(data);
        $('#' + type + 'List').hide();
    });
}
$(function()
{
    if(link == 'true') showLink(buildID, type, param);
})
