/**
 * Add new line for link product.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewLine(e)
{
    const newLine = $(e.target).closest('.form-row').clone();

    newLine.find('.addLine').on('click', addNewLine);
    newLine.find('.removeLine').on('click', removeLine);
    newLine.find('input[name^=modules]').val('').attr('name', 'modules[' + index + ']').attr('id', 'modules[' + index + ']');
    newLine.find('select[name^=programs]').val('').attr('name', 'programs[' + index + ']').attr('id', 'programs[' + index + ']');
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
