window.toggleLineByProgram = function(e)
{
    $('div[data-name=line]').toggleClass('hidden', $(e.target).val() == 0)
}
