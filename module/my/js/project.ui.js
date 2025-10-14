window.onRenderProjectNameCell = function(result, info)
{
    if(info.col.name === 'name' && info.row.data.delay > 0)
    {
        result[0].props.className = 'overflow-hidden';
        result[result.length] = {html:'<span class="label danger-pale ml-1 flex-none nowrap">' + delayInfo.replace('%s', info.row.data.delay) + '</span>', className:'flex items-end', style:{flexDirection:"column"}};
    }

    return result;
}

/**
 * 提示并删除项目。
 * Delete project with tips.
 *
 * @param  int    projectID
 * @param  string projectName
 * @access public
 * @return void
 */
window.confirmDelete = function(projectID, projectName)
{
    zui.Modal.confirm({message: confirmDeleteTip.replace('%s', projectName), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('project', 'delete', 'projectID=' + projectID)});
    });
}
