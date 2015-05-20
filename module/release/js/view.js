function showLink(releaseID, type, param)
{
    var method = type == 'story' ? 'linkStory' : 'linkBug';
    $.get(createLink('release', method, 'releaseID=' + releaseID + (typeof(param) == 'undefined' ? '' : param)), function(data)
    {
        var obj = type == 'story' ? '.tab-pane#stories .linkBox' : '.tab-pane#bugs .linkBox';
        $(obj).html(data);
        $('#' + type + 'List').hide();
    });
}
$(function()
{
    if(link == 'true') showLink(releaseID, type, param);
})
