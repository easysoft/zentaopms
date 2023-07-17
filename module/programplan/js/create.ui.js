window.onRenderRow = function(row, rowIdx, data)
{
    if(!data || !data.planIDList) return;

    row.children('.form-batch-row-actions').children('[data-type=delete]').remove();
};

window.onChangeExecutionType = function(event)
{
    loadPage($.createLink('programplan', 'create', `projectID=${projectID}&productID=${productID}&planID=${planID}&type=` + $(event.target).val()));
};

/**
 * Add row errors.
 *
 * @param  array  $rowErrors
 * @access public
 * @return void
 */
window.addRowErrors = function(rowErrors)
{
    var errorFieldID, errorTip, errorHTML;
    $('.text-danger.help-text').remove();
    $('#dataform input').removeClass('has-error');
    var index = 0;
    var alterError = '';
    $('input[name^=name]').each(function()
    {
        if($(this).val() == '') return true;

        if(typeof rowErrors[index] == 'object')
        {
            for(var errorField in rowErrors[index])
            {
                $errorTD  = $(this).closest('tr').find('input[name^=' + errorField + ']').closest('td');
                errorTip  = rowErrors[index][errorField];
                errorHTML = '<div id="help' + errorField + index + '" class="text-danger help-text">' + errorTip + '</div>';
                $errorTD.append(errorHTML);
                $errorTD.find('input').addClass('has-error');
            }
        }
        if(typeof rowErrors['percent'] == 'string')
        {
            errorFieldID = $(this).closest('tr').find('input[name^=percent]').attr('id');
            errorHTML    = '<div id="help' + errorFieldID + '" class="text-danger help-text">' + rowErrors['percent'] + '</div>';
            $('#' + errorFieldID).closest('td').append(errorHTML);
            $('#' + errorFieldID).closest('td').find('input').addClass('has-error');
        }

        index ++;
    });
};
