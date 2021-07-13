$(function()
{
    $('[name=mode]').change(function()
    {
        var mode = $(this).val();
        if(mode == 'classic') $('#modeTips').html(newTips);
        if(mode == 'new')     $('#modeTips').html(classicTips);
    })
})
