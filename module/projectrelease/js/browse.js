$(function()
{
    /* Update table summary text. */
    var $rows  = $('#releaseList').find('tbody>tr');

    var normal    = 0;
    var terminate = 0;
    $rows.each(function()
    {
        var $row = $(this);
        var data = $row.data();
        if(data.type === 'normal') normal++;
        if(data.type === 'terminate') terminate++;
    });

    var summary = '';
    if(type != 'all')
    {
        summary =  pageSummary.replace('%s', $rows.length);
    }
    else
    {
        summary = pageAllSummary.replace('%total%', $rows.length).replace('%normal%', normal).replace('%terminate%', terminate);
    }
    $('.table-statistic').html(summary);
});
