$(function()
{
    var heightType = $("[name='heightType']:checked").val();
    setCardCount(heightType);
    $('#colWidth, #minColWidth, #maxColWidth').attr('onkeyup', 'value=value.match(/^\\d+$/) ? value : ""');
    $('#colWidth, #minColWidth, #maxColWidth').attr('maxlength', '3');
    var fluidBoard = $("#mainContent input[name='fluidBoard'][checked='checked']").val() || 0;
    $('#colWidth').attr('disabled', fluidBoard == 1);
    $('#minColWidth, #maxColWidth').attr('disabled', fluidBoard == 0);
    $(document).on('change', "#mainContent input[name='fluidBoard']", function(e)
    {
        $('#colWidth').attr('disabled', e.target.value == 1);
        $('#minColWidth, #maxColWidth').attr('disabled', e.target.value == 0);
    })
});
