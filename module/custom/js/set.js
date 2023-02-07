function addItem(clickedButton)
{
    $(clickedButton).parent().parent().after(itemRow);
}

function delItem(clickedButton)
{
    $(clickedButton).parent().parent().remove();
}

$('[name*=unitList]').change(function()
{
    var defaultCurrency = $('#defaultCurrency').val();
    $('#defaultCurrency').empty().append('<option></option>');
    $('[name*=unitList]').each(function()
    {
        if($(this).prop('checked'))
        {
            var text     = $(this).parent().html();
            var firstStr = $(this).val() + '">';

            text = text.substring(text.lastIndexOf(firstStr) + firstStr.length, text.lastIndexOf('<'));
            $('#defaultCurrency').append("<option value='" + $(this).val() + "'>" + text + '</option>');
                                                                                }
    });

     $('#defaultCurrency').val(defaultCurrency);
     $("#defaultCurrency").trigger("chosen:updated");
});

$('#submit').click(function()
{
    if(module == 'testcase' && field == 'review' && stopSubmit && oldNeedReview)
    {
        var needReview = $('input:radio[name="needReview"]:checked').val();
        stopSubmit     = false;

        if(needReview == 0)
        {
            $.post(createLink('testcase', 'ajaxGetReviewCount'), function(count)
            {
                if(count == 0)
                {
                    $('#submit').click();
                    return true;
                }

                bootbox.confirm(confirmReviewCase, function(result)
                {
                    if(result) $('#submit').append("<input type='text' class='hidden' name='reviewCase' value='1'>");
                    $('#submit').click();
                })
            })

            return false;
        }
    }
})

$('[name*=unitList]').change();
