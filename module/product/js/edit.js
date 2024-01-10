$('#program').change(function()
{
    var programID = $(this).val();

    $.get(createLink('product', 'ajaxGetLine', 'programID=' + programID), function(data)
    {
        $('#line').replaceWith(data);
        $('#line').siblings('.picker').remove();
        $('#line').picker();
    })
});
