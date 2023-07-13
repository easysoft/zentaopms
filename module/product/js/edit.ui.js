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
        let $picker = $('#line').zui('picker');
        $picker.render(JSON.parse(data));
        $picker.$.setValue('');
    })
}
