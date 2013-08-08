function addItem(clickedButton)
{
    $(clickedButton).parent().parent().after(itemRow);
}

function delItem(clickedButton)
{
    $(clickedButton).parent().parent().remove();
}

$(function()
{
    $('#' + module + 'Tab').addClass('active');
    $('#' + field + 'Tab').addClass('active');
})

