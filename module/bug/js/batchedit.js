/**
 * Set duplicate field.
 * 
 * @param  string $resolution 
 * @access public
 * @return void
 */
function setDuplicate(resolution, bugID)
{
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox' + bugID).show();
    }
    else
    {
        $('#duplicateBugBox' + bugID).hide();
    }
}

$(function()
{
    $firstTr = $('.table-form').find('tbody tr:first');
    $firstTr.find('td select').each(function()
    {
        $(this).find("option[value='ditto']").remove();
        $(this).trigger("chosen:updated");
    });
})

$(document).on('change', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index  = $(this).closest('td').index();
        var row    = $(this).closest('tr').index();
        var $tbody = $(this).closest('tr').parent();

        if($(this).attr('name').indexOf('resolutions') != -1)
        {
            index  = $(this).closest('tr').closest('td').index();
            row    = $(this).closest('tr').closest('td').parent().index();
            $tbody = $(this).closest('tr').closest('td').parent().parent();
        }

        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $tbody.children('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }

        $(this).val(value);
        $(this).trigger("chosen:updated");
        $(this).trigger("change");
    }
})
