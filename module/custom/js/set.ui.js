window.addRow = function()
{
    if($('#customFieldRow .form-label').text() == 'addRow')
    {
        $('#customFieldRow .form-label').html('<input class="form-control key-row" type="text" autocomplete="off" name="keys[]">');
    }

    $(this).closest('.form-row').after($('#customFieldRow').html());
    $(this).closest('.form-row').next('.form-row').find('.add-item').on('click', addRow);
    $(this).closest('.form-row').next('.form-row').find('.del-item').on('click', removeRow);
}

window.removeRow = function()
{
    $(this).closest('.form-row').remove();
}

window.changeReview = function(e)
{
    $('.close-review, .open-review').addClass('hidden');
    if(e.target.value > 0)
    {
        $('.close-review').removeClass('hidden');
    }
    else
    {
        $('.open-review').removeClass('hidden');
    }
}

window.changeUnit = function()
{
    const defaultCurrency      = $('[name=defaultCurrency]').val();
    let   defaultCurrencyItems = new Array();
    let   index                = 0;
    $('[name^=unitList]').each(function()
    {
        if($(this).prop('checked'))
        {
            let   text     = $(this).parent().html();
            const firstStr = $(this).val() + '">';

            text = text.substring(text.lastIndexOf(firstStr) + firstStr.length, text.lastIndexOf('<'));
            defaultCurrencyItems[index] = {'value': $(this).val(), 'text': text};

            index ++;
        }
    });

    let $defaultCurrencyPicker = $('[name=defaultCurrency]').zui('picker');
    $defaultCurrencyPicker.render({items: defaultCurrencyItems});
    $defaultCurrencyPicker.$.setValue(defaultCurrency);
}

window.savaTestcaseReview = function()
{
    const url  = $("#settingForm").attr('action');
    const form = new FormData($("#settingForm")[0]);
    if(stopSubmit && oldNeedReview)
    {
        stopSubmit = false;

        const needReview = $('input[name="needReview"]:checked').val();
        if(needReview == 0)
        {
            $.ajaxSubmit({
                url: $.createLink('testcase', 'ajaxGetReviewAmount'),
                onComplete: function(count)
                {
                    stopSubmit = true;

                    if(count == 0)
                    {
                        $.ajaxSubmit({url, data:form});
                        return true;
                    }

                    zui.Modal.confirm(confirmReviewCase).then((result) =>
                    {
                        if(result) form.append('reviewCase', '1');
                        $.ajaxSubmit({url, data:form});
                    })
                }
            });

            return false;
        }
    }

    $.ajaxSubmit({url, data:form});
}

window.addGrade = function()
{
    let newRow   = $(this).closest('tr').clone();
    let maxIndex = $(this).closest('tbody').find('tr').length;
    newIndex = parseInt(maxIndex);

    newRow.find('input').val('');
    newRow.find('.btn-delete-grade').removeClass('hidden');
    newRow.find("input[type=hidden]").val(newIndex);
    newRow.find('td.index').text(newIndex);
    $(this).closest('tbody').append(newRow);

    $(this).remove();
};
