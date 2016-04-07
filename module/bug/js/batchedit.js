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

$(document).on('click', '.chosen-with-drop', function()
{
    var $select = $(this).prev('select');
    oldValue = $select.val();
})

$(document).on('change', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var table = $(this).closest('tr').parent();

        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }

        var valueStr = ',' + $(this).find('option').map(function(){return $(this).val();}).get().join(',') + ',';
        if(valueStr.indexOf(',' + value + ',') != -1)
        {
            $(this).val(value);
        }
        else
        {
            alert(dittoNotice);
            $(this).val(oldValue);
        }

        $(this).trigger("chosen:updated");
        $(this).trigger("change");
    }
})
