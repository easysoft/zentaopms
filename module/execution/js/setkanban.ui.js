window.changeColWidth = function (e)
{
    if(e.target.value == 1)
    {
        $('#colWidth').attr('disabled', true);
        $('#minColWidth').removeAttr('disabled');
        $('#maxColWidth').removeAttr('disabled');
    }
    else
    {
        $('#colWidth').removeAttr('disabled');
        $('#minColWidth').attr('disabled', true);
        $('#maxColWidth').attr('disabled', true);
    }
}

window.changeLaneHeight = function (e)
{
    if(e.target.value == 'custom')
    {
        $('#displayCards').closest('.form-group').removeClass('hidden');
    }
    else
    {
        $('#displayCards').closest('.form-group').addClass('hidden');
    }
}

window.setCardCount = function (e)
{
    if(e.target.value == 'custom')
    {
        $('#cardBox').removeClass('hidden');
    }
    else
    {
        $('#cardBox').addClass('hidden');
    }
}
