$(function()
{
    $('input[name^="mine"]').click(function()
    {
        var mine = $(this).is(':checked') ? 1 : 0;
        $.cookie('mine', mine, {expires:config.cookieLife, path:config.webRoot});
    })

    $('#programTableList').on('sort.sortable', function(e, data)
    {   
        var list = ''; 
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('project', 'updateOrder'), {'projects' : list, 'orderBy' : orderBy});
    });
})
