function showLink(planID, type, orderBy, param)
{
    var method = type == 'story' ? 'linkStory' : 'linkBug';
    $.get(createLink('productplan', method, 'planID=' + planID + (typeof(param) == 'undefined' ? '' : param) + (typeof(orderBy) == 'undefined' ? '' : "&orderBy=" + orderBy)), function(data)
    {
        var obj = type == 'story' ? '.tab-pane#stories .linkBox' : '.tab-pane#bugs .linkBox';
        $(obj).html(data);
        $('#' + type + 'List').hide();
    });
}
$(function()
{
    if(link == 'true') showLink(planID, type, orderBy, param);
})
