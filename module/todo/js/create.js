$(function()
{
    selectNext();
    $('#date').change(function()
    {
        var selectTime = $(this).val() != today ? start : nowTime;
        $('#begin').val(selectTime);
        $('#begin').trigger("chosen:updated");
        selectNext();
    })

    $('#cycle').change(function()
    {
        if($(this).prop('checked'))
        {
            $('.cycleConfig').removeClass('hidden');
            $('#switchDate').closest('.input-group-addon').addClass('hidden');
            $('#type').closest('tr').addClass('hidden');
            loadList('custom'); //Fix bug 3278.
        }
        else
        {
            $('.cycleConfig').addClass('hidden');
            $('#switchDate').closest('.input-group-addon').removeClass('hidden');
            $('#type').closest('tr').removeClass('hidden');
        }
    });
    $('ul.nav-tabs a').click(function()
    {
        if($(this).data('type'))$('input[id*=type][id*=config]').val($(this).data('type'));
    });
})

/**
 * Show appoint date.
 *
 * @param  switcher $switcher
 * @access public
 * @return void
 */
function showAppointDate(switcher)
{
    if(switcher.checked)
    {
        $('#date').attr('disabled','disabled');
        $('#dayInput').attr('disabled','disabled');
        $('.appoint').removeClass('hidden');
    }
    else
    {
        $('#date').removeAttr('disabled');
        $('#dayInput').removeAttr('disabled');
        $('.appoint').addClass('hidden');
    }
}

/**
 * Set days by appoint month.
 *
 * @param  appointMonth $appointMonth
 * @access public
 * @return void
 */
function setDays(appointMonth)
{
    /* Get last day in appoint month. */
    var date = new Date();
    date.setMonth(appointMonth);
    date.setDate(0);
    var appointMonthLastDay = date.getDate();

    $('#appointDay').empty('');
    for(var i = 1; i <= appointMonthLastDay ; i++)
    {
        html = "<option value='" + i + "' title='" + i + "' data-keys='" + i + "'>" + i + "</option>";

        $('#appointDay').append(html);
    }
}
