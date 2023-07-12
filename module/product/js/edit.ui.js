$(function()
{
    setWhite($('[name=acl]:checked'));
});

function toggleLineByProgram(obj)
{
    var programID = $(obj).val();
    $('#lineBox').toggleClass('hidden', programID == 0)
    if(programID == 0) return;

    $.get($.createLink('product', 'ajaxGetLine', 'programID=' + programID), function(data)
    {
        $('#line').remove();
        $('#lineBox .form-group').append("<div class='form-group-wrapper picker-box' id='line'></div>");
        return new zui.Picker('#line', JSON.parse(data));
    })
}
