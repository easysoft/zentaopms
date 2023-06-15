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
    zui.Modal.confirm({message: confirmDelete, icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('job', 'delete', 'jobID=' + jobID)});
    });
}
