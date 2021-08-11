$(function()
{
    if(!moduleTree || moduleTree.length == 0)
    {
        var contentHeight = $('.no-content').parent().innerHeight();
        var titleHeight   = $('.cell div:nth-child(1)').innerHeight();

        var height = $(document).height() - $('#header').height() - parent.$('#appsBar').height() - (2 * parseInt($('#main').css('padding-top')));
        $('.main-content .cell').height(height);
        $('.no-content').parent().css('padding-top', (height - contentHeight)/2 - titleHeight + 'px');
    }

    $('#main .main-content li.has-list').addClass('open in');
})
