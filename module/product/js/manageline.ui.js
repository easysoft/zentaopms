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

/**
 * Check if there are products under the product line.
 *
 * @access public
 * @return void
 */
window.isProductLineEmpty = function()
{
    const obj       = this;
    let lineInputID = $(obj).siblings("div[data-name^=modules]").find('input').attr('id');
    let lineID      = lineInputID.replace('modules_id', '');

    $.get($.createLink('product', 'ajaxGetProductByLine', 'lineID=' + lineID), function(data)
    {
        if(data && !hasPrompted)
        {
            zui.Modal.confirm(changeProgramTip).then((res) =>
            {
                if(!res)
                {
                    let preProgram    = sessionStorage.getItem($(obj).find("input[name^=programs]").attr('id'));
                    let programPicker = $(obj).find("input[name^=programs]").zui('picker');
                    programPicker.$.setValue(preProgram);

                    hasPrompted = true;
                }
                else
                {
                    hasPrompted = false;

                    let programInputID  = $(obj).find("input[name^=programs]").attr('id');
                    let programInputVal = $(obj).find("input[name^=programs]").val();
                    sessionStorage.setItem(programInputID, programInputVal);
                }
            });
        }
        else
        {
            hasPrompted = false;

            let programInputID  = $(obj).find("input[name^=programs]").attr('id');
            let programInputVal = $(obj).find("input[name^=programs]").val();
            sessionStorage.setItem(programInputID, programInputVal);
        }
    })
}

$(function()
{
    hasPrompted = false;

    setTimeout(function()
    {
        $("input[name^=programs]").each(function()
        {
            let selectId      = $(this).attr('id');
            let selectedValue = $(this).val();

            sessionStorage.setItem(selectId, selectedValue);
        })
    }, 50);
})
