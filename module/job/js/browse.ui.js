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
 * @param  int    jobID
 * @access public
 * @return void
 */
window.confirmDelete = function(jobID)
{
    if(window.confirm(confirmDelete))
    {
        $.ajaxSubmit({url: $.createLink('job', 'delete', 'jobID=' + jobID)});
    }
}