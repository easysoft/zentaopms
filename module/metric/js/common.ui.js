window.addUnit = function(e)
{
    if($(e.target).prop('checked'))
    {
        $('#unitBox').addClass('hidden');
        $('#addUnitBox').removeClass('hidden');
        $("[name^='customUnit']").prop('checked', true);
    }
    else
    {
        $('#unitBox').removeClass('hidden');
        $('#addUnitBox').addClass('hidden');
        $("[name^='customUnit']").prop('checked', false);
    }
}
