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
    zui.Modal.confirm({message: confirmDeleteTip.replace('%s', projectName), icon:'icon-info-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('project', 'delete', 'projectID=' + projectID)});
    });
}
