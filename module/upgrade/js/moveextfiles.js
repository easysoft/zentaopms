$('#checkAll').click(function()
{
    if($(this).is(':checked'))
    {
        $("[name^='files']").prop('checked', true);
    }
    else
    {
        $("[name^='files']").prop('checked', false);
    }
})

$("[name^='files']").click(function()
{
    if($(this).is(':checked'))
    {
        var checked = true;
        $("[name^='files']").each(function()
        {
            if(!$(this).is(':checked')) checked = false;
        });
        $('#checkAll').prop('checked', checked);
    }
    else
    {
        $('#checkAll').prop('checked', false);
    }
})

/**
 * Refresh page.
 *
 * @access public
 * @return void
 */
function refreshPage()
{
    location.reload(true);
}
