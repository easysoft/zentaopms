window.changeMode = function()
{
    const mode = $('[name=mode]:checked').val();
    $('#otherLaneBox').toggleClass('hidden', mode != 'sameAsOther');
}
