window.toggleLineByProgram = function(e)
{
    const programID = $(e.target).val();
    $('div[data-name=line]').toggleClass('hidden', programID == 0)

    if(programID == 0) return;
    $.getJSON($.createLink('product', 'ajaxGetLine', 'programID=' + programID), function(data)
    {
        const $picker = $('[name=line]').zui('picker');
        $picker.render(data);
        $picker.$.setValue('');
    })
}
