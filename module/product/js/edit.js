$(function()
{
    if(programID == 0) $("#line").closest('tr').addClass('hidden');
});

$('#program').change(function()
{
    var programID = $(this).val();
    programID > 0 ? $("#line").closest('tr').removeClass('hidden') : $("#line").closest('tr').addClass('hidden');

    $.get(createLink('product', 'ajaxGetLine', 'programID=' + programID), function(data)
    {
        $('#line_chosen').remove();
        $('#line').replaceWith(data);
        $('#line').chosen();
    })
});
