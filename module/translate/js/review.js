function reloadStatus(translationID)
{
    $.get(createLink('translate', 'ajaxGetStatus', 'id=' + translationID), function(data)
    {
        var $td = $('#mainContent table tbody tr .pass[data-id=' + translationID + ']').closest('td');
        $td.closest('tr').find('.status').html(data)
        $td.html('');
    });
}
$(function()
{
    adjustKeyWidth();
    $('#mainContent table tbody [id^=idList]').change(function()
    {
        setTimeout(function()
        {
            var isCheckAll = true;
            $('#mainContent table tbody [id^=idList]').each(function()
            {
                if(!$(this).prop('checked')) isCheckAll = false;
            })
            if(isCheckAll) $('.check-all').addClass('checked');
            if(!isCheckAll) $('.check-all').removeClass('checked');
        }, 50);
    })

    $('#mainContent table tbody tr .pass').click(function()
    {
        var $td = $(this).closest('td');
        var $tr = $td.closest('tr');
        var id  = $(this).data('id');
        $.get($(this).attr('href'), function()
        {
            $.get(createLink('translate', 'ajaxGetStatus', 'id=' + id), function(data)
            {
                $tr.find('.status').html(data)
                $td.html('');
            });
        });
        return false;
    })
});
