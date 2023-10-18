window.operateDept = function(event, item, index)
{
    var $target    = $(event.target);
    var $button    = $target.hasClass('icon') ? $target.closest('button') : $target;
    var editLink   = editLinkTemp;
    var deleteLink = deleteLinkTemp;
    var deptID     = $target.closest('.tree-item').attr('z-key');
    if($target.hasClass('icon-edit') || $target.children('.icon-edit').length > 0)
    {
        $button.attr('data-toggle', 'modal');
        $button.attr('data-size', 'sm');
        $button.attr('data-url', editLink.replace('{id}', deptID));
    }
    else if($target.hasClass('icon-trash') || $target.children('.icon-trash').length > 0)
    {
        $button.attr('data-confirm', deleteTip);
        $button.attr('data-url', deleteLink.replace('{id}', deptID));
        $button.addClass('ajax-submit');
    }
}
