$(document).on('click', '.task-toggle', function(e)
{   
    var $toggle = $(this);
    var id = $(this).data('id');
    var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
    $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

    e.stopPropagation();
    e.preventDefault();
}); 
