/**
 * Toggle program.
 *
 * @access public
 * @return void
 */
function toggleProgram()
{
    $('#programBox').toggleClass('hidden', !$('#program').prop('checked'));
}

/**
 * Toggle product.
 *
 * @access public
 * @return void
 */
function toggleProduct()
{
    $('#productBox').toggleClass('hidden', !$('#product').prop('checked'));
}

/**
 * Toggle project.
 *
 * @access public
 * @return void
 */
function toggleProject()
{
    $('#projectBox').toggleClass('hidden', !$('#project').prop('checked'));
}

/**
 * Toggle execution.
 *
 * @access public
 * @return void
 */
function toggleExecution()
{
    $('#executionBox').toggleClass('hidden', !$('#execution').prop('checked'));
}

/**
 * Select all.
 *
 * @param  obj    $obj
 * @access public
 * @return void
 */
function selectAll(e)
{
    $(e.target).closest('.check-list-inline').find('input[type=checkbox]').prop('checked', $(e.target).prop('checked'));
    $('div.group-item input[name^=actions]').each(function()
    {
        $(this).trigger('change');
    });
}

/**
 * Select items.
 *
 * @param  obj    $obj
 * @access public
 * @return void
 */
function selectItems(e)
{
    $(e.target).closest("div[id$='ActionBox']").find('.check-list-inline input[type=checkbox]').prop('checked', $(e.target).prop('checked'));
    $('div.group-item input[name^=actions]').each(function()
    {
        $(this).trigger('change');
    });
}

$(function()
{
    $('div.group-item input[name^=actions]').each(function()
    {
        $(this).trigger('change');
    });

    $(document).on('click', '.action-item input[type=checkbox]', function()
    {
        let allChecked     = true;
        const checkedCount = $(this).closest('.check-list-inline').find('input[name^=actions]:checked').length;
        const totalCount   = $(this).closest('.check-list-inline').find('input[name^=actions]').length;
        if(checkedCount < totalCount) allChecked = false;
        $(this).closest("div[id$='ActionBox']").find('input[name^="allchecker"]').prop('checked', allChecked);
    })
});

/**
 * Toggle program.
 *
 * @access public
 * @return void
 */
function toggleBox(e)
{
    let   allChecked   = true;
    const $obj         = $(e.target);
    const checkedCount = $obj.closest('.form-group').find('input[name^=actions]:checked').length;
    const totalCount   = $obj.closest('.form-group').find('input[name^=actions]').length;
    if(checkedCount < totalCount) allChecked = false;
    $('.group-item input[name^="actionallchecker"]').prop('checked', allChecked);

    let id = $obj.attr('id');
    if(id == 'program')   toggleProgram();
    if(id == 'product')   toggleProduct();
    if(id == 'project')   toggleProject();
    if(id == 'execution') toggleExecution();
    if($('#' + id + 'ActionBox').length == 1) $('#' + id + 'ActionBox').toggle($obj.prop('checked'));

    $("div[id$='ActionBox']").each(function()
    {
        let allChecked     = true;
        const checkedCount = $(this).find('input[name^=actions]:checked').length;
        const totalCount   = $(this).find('input[name^=actions]').length;
        if(checkedCount < totalCount) allChecked = false;
        $(this).find('input[name^="actionallchecker"]').prop('checked', allChecked);
    });
}
