/**
 * 处理项目类型改变的交互。
 * Handle project type change style.
 *
 * @param  string $type
 * @access public
 * @return void
 */
function changeType(type)
{
    $('.project-type-1, .project-type-0').removeClass('primary-pale');
    $('.project-type-' + type).addClass('primary-pale');
    $('input[name=hasProduct]').val(type);
}

/**
 * 设置计划结束时间。
 * Set plan end date.
 *
 * @access public
 * @return void
 */
function setDate()
{
    const delta = $('input[name=delta]:checked').val();
    computeEndDate(delta);
}

/**
 * Set acl list when change program.
 *
 * @access public
 * @return void
 */
window.setParentProgram = function()
{
    const programID = $('#parent').val();
    const link = $.createLink('project', 'create', 'model=' + model + '&program=' + programID);
    loadPage(link, '#aclList');
}
