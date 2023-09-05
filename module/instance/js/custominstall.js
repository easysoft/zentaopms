$(function()
{
    $('input[type=number]').on('change', function(event)
    {
        if($(event.target).val() <= 1) $(event.target).val(1);
    });
});
