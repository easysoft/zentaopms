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
            $('#type').find('option').each(function()
            {
                  if($(this).val() != 'custom') $(this).addClass('hidden');
            })
        }
        else
        {
            $('.cycleConfig').addClass('hidden');
            $('#switchDate').closest('.input-group-addon').removeClass('hidden');
            $('#type').find('option').removeClass('hidden');
        }
    });
    $('ul.nav-tabs a').click(function()
    {
        if($(this).data('type'))$('input[id*=type][id*=config]').val($(this).data('type'));
    });
})
