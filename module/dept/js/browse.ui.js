window.operateDept = function(event, item, index)
{
    var $target    = $(event.target);
    var $button    = $target.hasClass('icon') ? $target.closest('button') : $target;
    var editLink   = editLinkTemp;
    var deleteLink = editLinkTemp;
    var deptID     = $target.closest('.tree-item-content').attr('id');
    if($target.hasClass('icon-edit') || $target.children('.icon-edit').length > 0)
    {
        $button.attr('data-toggle', 'modal');
        $button.attr('data-url', editLinkTemp.replace('{id}', deptID));
    }
    else if($target.hasClass('icon-trash') || $target.children('.icon-trash').length > 0)
    {
        $button.attr('data-confirm', deleteTip);
        $button.attr('data-url', deleteLinkTemp.replace('{id}', deptID));
        $button.addClass('ajax-submit');
    }
}
