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
    $('#colWidth, #minColWidth, #maxColWidth').attr('onkeyup', 'value=value.match(/^\\d+$/) ? value : ""');
    $('#colWidth, #minColWidth, #maxColWidth').attr('maxlength', '3');
    var fluidBoard = $("#mainContent input[name='fluidBoard'][checked='checked']").val() || 0;
    $('#colWidth').attr('disabled', fluidBoard == 1);
    $('#minColWidth, #maxColWidth').attr('disabled', fluidBoard == 0);
    $(document).on('change', "#mainContent input[name='fluidBoard']", function(e)
    {
        $('#colWidth').attr('disabled', e.target.value == 1);
        $('#minColWidth, #maxColWidth').attr('disabled', e.target.value == 0);
        if(e.target.value == 0 && $('#minColWidthLabel, #maxColWidthLabel'))
        {
            $('#minColWidthLabel, #maxColWidthLabel').remove();
            $('#minColWidth, #maxColWidth').removeClass('has-error');
        }
        else if(e.target.value == 1 && $('#colWidthLabel'))
        {
            $('#colWidthLabel').remove();
            $('#colWidth').removeClass('has-error');
        }
    })
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
