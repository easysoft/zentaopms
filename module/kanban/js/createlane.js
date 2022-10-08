$(document).ready(function()
{
    initColorPicker();

    $('input[name=mode]').change(function() 
    {
        $('#otherLane').parents('tr').toggle($(this).val() == 'sameAsOther');
    });
})
