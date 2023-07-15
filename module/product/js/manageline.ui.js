/**
 * Add new line for link product.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewLine(e)
{
    const $formRow = $(e.target).closest('.form-row');
    const newLine  = $formRow.clone();

    newLine.find('.addLine').on('click', addNewLine);
    newLine.find('.removeLine').on('click', removeLine);
    newLine.find('[name^=modules]').val('').attr('name', 'modules[' + index + ']').attr('id', 'modules_' + index);
    newLine.find('[name^=programs]').closest('.picker-box').attr('id', 'programs_' + index).picker($.extend({}, $formRow.find('.picker-box[id^=programs]').zui('picker').options, {name:"programs[" + index + "]"}));
    $(e.target).closest('.form-row').after(newLine);

    index ++;
}

/**
 * Remove line for link product.
 *
 * @param  obj    e
 * @access public
 * @return void
 */
function removeLine(e)
{
    if($('.line-row-add').length == 1) return;

    const obj = e.target;
    $(obj).closest('.form-row').remove();
}
