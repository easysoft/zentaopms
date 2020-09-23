$(function()
{
    $('#PRJMine1').click(function()
    {
        var PRJMine = $(this).is(':checked') ? 1 : 0;
        $.cookie('PRJMine', PRJMine, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    $('#projectTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('program', 'PRJUpdateOrder'), {'projects' : list, 'orderBy' : orderBy});
    });
});

$('#project' + programID).addClass('active');
$(".tree .active").parent('li').addClass('active');
