$(function()
{
    setWhite();
});

/**
 * 移除复制项目产生的某个控件的提示信息。
 * Remove the tip of a control generated by copy project.
 *
 * @access public
 * @return void
 */
function removeTips()
{
    const $formGroup = $(this).closest('.form-group');
    $formGroup.removeClass('has-warning');
    $formGroup.find('.has-warning').removeClass('has-warning');
    $formGroup.find('.form-tip').remove();
}

/**
 * 移除复制项目时产生的所有控件的提示信息。
 * Remove all tips generated when copying projects.
 *
 * @access public
 * @return void
 */
function removeAllTips()
{
    $('.has-warning').removeClass('has-warning');
    $('.text-warning').remove();
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

window.toggleStoryType = function(e)
{
    if(e.target.value == 'requirement' && !e.target.checked)
    {
        $('input[value=epic]').prop('checked', false);
    }
    else if(e.target.value == 'epic' && e.target.checked)
    {
        $('input[value=requirement]').prop('checked', true);
    }
}