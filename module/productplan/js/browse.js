$(document).on('click', '.task-toggle', function(e)
{   
    var $toggle = $(this);
    var id = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

    e.stopPropagation();
    e.preventDefault();
});
$(function()
{
    $('#productplanList tbody tr').each(function()
    {
        var content = $(this).find('td.content div').html();
        if(content.indexOf('<br') >= 0)
        {
            $(this).find('td.content').append("<a href='###' class='more'><i class='icon icon-chevron-double-down'></i></a>");
        }
    });
})
$(document).on('click', 'td.content .more', function(e)
{
    var $toggle = $(this);
    if($toggle.hasClass('open'))
    {
        $toggle.removeClass('open');
        $toggle.closest('.content').find('div').css('height', '25px');
    }
    else
    {
        $toggle.addClass('open');
        $toggle.closest('.content').find('div').css('height', 'auto');
    }
});
