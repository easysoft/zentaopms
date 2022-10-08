$(function()
{
    var heightType = $("[name='heightType']:checked").val();
    setCardCount(heightType);

    if(enableImport == 'off')
    {
        $("input[name^='importObjectList']").attr('disabled', 'disabled');
        $('td.objectBox').hide();
    }

    $("input[name='import']").change(function()
    {
        if($(this).val() == 'off')
        {
            $("input[name^='importObjectList']").attr('disabled', 'disabled');
            $('td.objectBox').hide();
        }
        else
        {
            $("input[name^='importObjectList']").removeAttr('disabled');
            $('td.objectBox').show();
        }
    })

    $("input[name^='importObjectList']").change(function()
    {
        if($("input:checked[name^=importObjectList]").length != 0 && !$('#emptyTip').is('.hidden')) $('#emptyTip').addClass('hidden');
    })

    $('#submit').click(function()
    {
        var enableImport     = $("input:checked[name='import']").val();
        var objectListLength = $("input:checked[name^=importObjectList]").length;

        if(enableImport == 'on' && objectListLength == 0 && vision != 'lite')
        {
            $('#emptyTip').removeClass('hidden');
            return false;
        }
    })
    handleKanbanWidthAttr();
})

/**
 * Set card count.
 *
 * @param  string $heightType
 * @access public
 * @return void
 */
function setCardCount(heightType)
{
    heightType != 'custom' ? $('#cardBox').addClass('hidden') : $('#cardBox').removeClass('hidden');
}
