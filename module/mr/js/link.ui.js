$(function()
{
    $('li.' + type + '>a').trigger('click');
});

window.createStorySortLink = function(col)
{
    var sort = col.name + '_asc';
    var currentOrderBy = 'id_asc';
    if(type == 'story') currentOrderBy = orderBy;
    if(sort == currentOrderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort).replace('{type}', 'story');
}

window.createBugSortLink = function(col)
{
    var sort = col.name + '_asc';
    var currentOrderBy = 'id_asc';
    if(type == 'bug') currentOrderBy = orderBy;
    if(sort == currentOrderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort).replace('{type}', 'bug');
}

window.createTaskSortLink = function(col)
{
    var sort = col.name + '_asc';
    var currentOrderBy = 'id_asc';
    if(type == 'task') currentOrderBy = orderBy;
    if(sort == currentOrderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort).replace('{type}', 'task');
}

window.showLink = function(obj)
{
    const $tabContent = $(obj).closest('.tab-pane');
    const link        = $tabContent.find('.link').data('url');
    $tabContent.load(link);
};
