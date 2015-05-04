$(function()
{
    selectNext();
    $('#date').change(function()
    {
        var selectTime = $(this).val() != today ? start : nowTime;
        $('#begin').val(selectTime);
        selectNext();
    })
})
