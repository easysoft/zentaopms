$(function()
{
    var endDate = $('#end').val();
    $('#isCat').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#longTimeBox').removeClass('hidden');
            $('#longTime').change();
        }
        else
        {
            $('#longTimeBox').addClass('hidden');
            $('#longTimeBox').find('#longTime').prop('checked', false).change();
        }
    });

    $('#longTime').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#end').val('').attr('disabled', 'disabled');
        }
        else
        {
            $('#end').val(endDate).removeAttr('disabled');
        }
    });

    $('#end').change(function()
    {
        var beginDate = $('#begin').val();
        var endDate   = $('#end').val();
        var begin     = new Date(beginDate.replace(/-/g,"/"));
        var end       = new Date(endDate.replace(/-/g,"/"));
        var time      = end.getTime() - begin.getTime();
        var days      = parseInt(time / (1000 * 60 * 60 * 24)) + 1;
        if(days != $("input:radio[name='delta']:checked").val()) $("input:radio[name='delta']:checked").attr('checked',false);
    })

    $('#isCat').change();

    $('#budgetUnit').change(function()
    {
        if($(this).val() != oldBudgetUnit)
        {
            $('#currentUnit').text(budgetUnitList[$(this).val()]);
            $('#changeUnitTip').modal({show: true});
        }
    })

    $('#cancel').click(function()
    {
        $('#isChangeUnit').val('false');
        $('#exchangeRate').val('');
    })

    $('#confirm').click(function()
    {
        var exchangeRate = $('#rate').val();
        if(!exchangeRate)
        {
            alert(exRateNotEmpty);
            return false;
        }

        $('#isChangeUnit').val('true');
        $('#exchangeRate').val(exchangeRate);
        $('#changeUnitTip').modal('hide');
    })
});
