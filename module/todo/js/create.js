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
            $('#type').val('custom');
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

    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });
})
