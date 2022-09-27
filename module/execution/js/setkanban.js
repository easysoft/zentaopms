$(function()
{
    var heightType = $("[name='heightType']:checked").val();
    setCardCount(heightType);

    var fluidBoard = $("#fluidBoard").val() || 0;
    $('#colWidth').attr('disabled', fluidBoard == 1);
    $('#minColWidth').attr('disabled', fluidBoard == 0);
    $('#maxColWidth').attr('disabled', fluidBoard == 0);
    $(document).on('change', "#mainContent input[name^=fluidBoard]", function(e)
    {
        $('#colWidth').attr('disabled', e.target.value == 1);
        $('#minColWidth').attr('disabled', e.target.value == 0);
        $('#maxColWidth').attr('disabled', e.target.value == 0);
    })
});
