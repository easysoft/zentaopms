/**
 * Update metriclib table structure.
 */
function updateMetriclib(key)
{
    $('#updateSQLs > #sql' + key).removeClass('hidden');
    $('#startUpdate').remove();

    $.getJSON($.createLink('admin', 'metriclib', 'key=' + key), function(resp)
    {
        if(resp.result == 'success')
        {
            $('#sql' + key).addClass('text-success').find('.icon').toggleClass('animate-spin icon-spinner-indicator icon-check');
            if(resp.key)
            {
                updateMetriclib(resp.key);
            }
            else
            {
                $('#updated').removeClass('hidden');
            }
        }
        else
        {
            zui.Modal.alert(resp.message).then(() => {loadCurrentPage()});
        }
    });
}
