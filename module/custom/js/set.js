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
    $('#' + module + 'Tab').addClass('btn-active-text');
    $('#' + field + 'Tab').addClass('active');
})

$('[name*=unitList]').change(function()
{
    var mainCurrency = $('#mainCurrency').val();
    $('#mainCurrency').empty().append('<option></option>');
    $('[name*=unitList]').each(function()
    {
        if($(this).prop('checked'))
        {
            var text     = $(this).parent().html();
            var firstStr = $(this).val() + '">';

            text = text.substring(text.lastIndexOf(firstStr) + firstStr.length, text.lastIndexOf('<'));
            $('#mainCurrency').append("<option value='" + $(this).val() + "'>" + text + '</option>');
                                                                                }
    });

     $('#mainCurrency').val(mainCurrency);
     $("#mainCurrency").trigger("chosen:updated");
});

$('[name*=unitList]').change();
