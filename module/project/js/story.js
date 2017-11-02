$(function()
{
    ajaxGetSearchForm();

    $('#storyList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i]).attr('data-id') + ',';
        $.post(createLink('project', 'storySort', 'projectID=' + projectID), {'storys' : list, 'orderBy' : orderBy}, function()
        {
            var $target = $(data.element[0]);
            $target.hide();
            $target.fadeIn(1000);
            order = 'order_asc'
            history.pushState({}, 0, createLink('project', 'story', "projectID=" + projectID + '&orderBy=' + order));
        });
    });

    fixedTfootAction('#projectStoryForm');
    fixedTheadOfList('#storyList');

    $('#module' + moduleID).addClass('active');
    $('#product' + productID).addClass('active');
});
