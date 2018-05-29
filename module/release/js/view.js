function showLink(releaseID, type, param)
{
    var method = type == 'story' ? 'linkStory' : 'linkBug';
    if(typeof(param) == 'undefined') param = '&browseType=' + type + '&param=0';
    if(type == 'leftBug') param += '&type=leftBug';
    $.get(createLink('release', method, 'releaseID=' + releaseID + param), function(data)
    {
        var $pane = $(type == 'story' ? '#stories' : (type == 'leftBug' ? '#leftBugs' : '#bugs'));
        $pane.find('.main-table').hide();
        var $linkBox = $pane.find('.linkBox').html(data).removeClass('hidden');
        $linkBox.find('[data-ride="table"]').table();
        $.toggleQueryBox(true, $linkBox.find('#queryBox'));
    });
}

$(function()
{
    if(link == 'true') showLink(releaseID, type, param);
    $('.nav.nav-tabs a[data-toggle="tab"]').on('shown.zui.tab', function(e)
    {
        var href = $(e.target).attr('href');
        var tabPane = $(href + '.tab-pane');
        if(tabPane.size() == 0) return;
        var formID = tabPane.find('.linkBox').find('form:last');
        if(formID.size() == 0) formID = tabPane.find('form:last');
    });
})
