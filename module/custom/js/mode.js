$(function()
{
    $('[name=mode]').change(function()
    {
        var mode = $(this).val();
        if(mode == 'new')     $('#modeTips').html(newTips);
        if(mode == 'classic') $('#modeTips').html(classicTips);
    })
})
