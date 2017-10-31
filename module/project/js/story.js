$(function()
{
    ajaxGetSearchForm();

    $('#storyList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i]).attr('data-id') + ',';
        $.post(createLink('project', 'storySort', 'projectID=' + projectID), {'storys' : list, 'orderBy' : orderBy});
    });

    fixedTfootAction('#projectStoryForm');
    fixedTheadOfList('#storyList');

    $('#module' + moduleID).addClass('active');
    $('#product' + productID).addClass('active');
});
