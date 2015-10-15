function showLink(releaseID, type, param)
{
    var method = type == 'story' ? 'linkStory' : 'linkBug';
    if(typeof(param) == 'undefined') param = '&browseType=' + type + '&param=0';
    if(type == 'leftBug') param += '&type=leftBug';
    $.get(createLink('release', method, 'releaseID=' + releaseID + param), function(data)
    {
        var obj = type == 'story' ? '.tab-pane#stories .linkBox' : (type == 'leftBug' ? '.tab-pane#leftBugs .linkBox' : '.tab-pane#bugs .linkBox');
        $(obj).html(data);
        $('#' + type + 'List').hide();

        var formID = type == 'story' ? '#unlinkedStoriesForm' : (type == 'leftBug' ? '#unlinkedLeftBugsForm' : '#unlinkedBugsForm');
        fixTfootAction(formID);
    });
}
$(function()
{
    if(link == 'true') showLink(releaseID, type, param);
})
