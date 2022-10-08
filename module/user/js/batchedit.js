$(document).ready(removeDitto());//Remove 'ditto' in first row.

/* Set ditto value. */
$(document).on('change', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var tbody = $(this).closest('tr').parent();

        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = tbody.children('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }

        $(this).val(value);
        $(this).trigger("chosen:updated");
        $(this).trigger("change");
    }
})

$('select[id^="visions"]').each(function()
{
    var i      = $(this).attr('id').replace(/[^0-9]/ig, '');
    var vision = $('#visions1 option:selected').val();

    $.post(createLink('user', 'ajaxGetGroup', "visions=" + vision + '&i=' + i + '&selected=' + $('#group' + i).val()), function(data)
    {
         $('#group' + i).replaceWith(data);
         $('#group' + i + '_chosen').remove();
         $('#group' + i).chosen();
    })
})
