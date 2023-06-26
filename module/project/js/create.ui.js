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

$(document).on('click', '#copyProjects button', function()
{
    const copyProjectID = $(this).hasClass('success-outline') ? 0 : $(this).data('id');
    setCopyProject(copyProjectID);
    zui.Modal.hide();
});

/**
 * Set copy project.
 *
 * @param  int $copyProjectID
 * @access public
 * @return void
 */
function setCopyProject(copyProjectID)
{
    const programID = $('#parent').val();
    loadPage($.createLink('project', 'create', 'model=' + model + '&programID=' + programID + '&copyProjectID=' + copyProjectID));
}

/**
 * Fuzzy search projects by project name.
 *
 * @access public
 * @return void
 */
$(document).on('keyup', '#projectName', function()
{
    var name = $(this).val();
    name = name.replace(/\s+/g, '');
    $('#copyProjects .project-block').hide();

    if(!name) $('#copyProjects .project-block').show();
    $('#copyProjects .project-block').each(function()
    {
        if($(this).text().includes(name) || $(this).data('pinyin').includes(name)) $(this).show();
    });
});
