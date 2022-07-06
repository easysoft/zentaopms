$(document).ready(function()
{
    removeDitto();//Remove 'ditto' in first row.
    if($('#batchCreateForm table thead tr th.c-title').width() < 170) $('#batchCreateForm table thead tr th.c-title').width('170');

    $(document).keydown(function(event)
    {
        if(event.ctrlKey && event.keyCode == 38)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('up');
        }
        else if(event.ctrlKey && event.keyCode == 40)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('down');
        }
        else if(event.keyCode == 38)
        {
            inputFocusJump('up');
        }
        else if(event.keyCode == 40)
        {
            inputFocusJump('down');
        }
    });

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        var fields = '';
        $('#formSettingForm > .checkboxes > .checkbox-primary > input:checked').each(function()
        {
            fields += ',' + $(this).val();
        });

        var link = createLink('custom', 'ajaxSaveCustomFields', 'module=testcase&section=custom&key=batchCreateFields');
        $.post(link, {'fields' : fields}, function()
        {
            showCheckedFields(fields);
            $('#formSetting').parent().removeClass('open');

            var fieldCount = $('#batchCreateForm .table thead>tr>th:visible').length;
            $('.form-actions').attr('colspan', fieldCount);

            if(fieldCount > 8)
            {
                $('#batchCreateForm > .table-responsive').removeClass('scroll-none');
                $('#batchCreateForm > .table-responsive').css('overflow', 'auto');
            }
            else
            {
                $('#batchCreateForm > .table-responsive').addClass('scroll-none');
                $('#batchCreateForm > .table-responsive').css('overflow', 'visible');
            }

            if($('#batchCreateForm table thead tr th.c-title').width() < 170) $('#batchCreateForm table thead tr th.c-title').width('170');
        });

        return false;
    });
});

/**
 * Add item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, itemIndex + 1);
    $('<tr class="addedItem">' + item  + '</tr>').insertAfter($(obj).closest('tr'));

    $(obj).closest('tr').next().find(".form-date").datepicker();
    $(obj).closest('tr').next().find('div[id$=_chosen]').remove();
    $(obj).closest('tr').next().find('.chosen').next('.picker').remove();
    $(obj).closest('tr').next().find('.chosen').chosen();

    itemIndex ++;
}

/**
 * Delete item.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    $(obj).closest('tr').remove();
}
