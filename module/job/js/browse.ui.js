window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort);
}

/**
 * 提示并删除版本。
 * Delete release with tips.
 *
 * @param  int    gogsID
 * @access public
 * @return void
 */
window.confirmDelete = function(gogsID)
{
    if(window.confirm(confirmDelete))
    {
        $.ajaxSubmit({url: $.createLink('gogs', 'delete', 'gogsID=' + gogsID)});
    }
}