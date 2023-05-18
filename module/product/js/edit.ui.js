$(function()
{
    setWhite($('[name=acl]:checked'));
    toggleLineByProgram();
});

function toggleLineByProgram()
{
    var programID = $('#program').val();
    $('#lineBox').toggleClass('hidden', programID == 0)
}
