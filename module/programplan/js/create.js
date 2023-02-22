$(function()
{
    $('input').on('change', function()
    {
        $(this).removeClass('has-error');
        $(this).closest('td').find('.text-danger.help-text').remove();
    });

    $('[data-toggle="popover"]').popover();
});
/**
 * Add item to create view of programplan.
 *
 * @param  obj    obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, itemIndex);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    var newItem = $('#names' + itemIndex).closest('tr');
    newItem.find('.form-date').datepicker();
    $("#output" + itemIndex).picker();
    $("#PM" + itemIndex).picker();
    $("#type" + itemIndex).chosen();
    $("#type_i__chosen").remove();
    itemIndex ++;
}

/**
 * Delete item.
 *
 * @param  obj    obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    if($('#planForm .table tbody').children().length < 2) return false;
    $(obj).closest('tr').remove();
}

/**
 * Add row errors.
 *
 * @param  array  $rowErrors
 * @access public
 * @return void
 */
function addRowErrors(rowErrors)
{
    var errorFieldID, errorTip, errorHTML;
    $('.text-danger.help-text').remove();
    $('input').removeClass('has-error');
    var index = 0;
    var alterError = '';
    $('input[name^=names]').each(function()
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
}
