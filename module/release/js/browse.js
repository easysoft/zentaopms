$(function()
{
    /* Update table summary text. */
    var $rows  = $('#releaseList').find('tbody>tr');

    var total     = 0;
    var normal    = 0;
    var terminate = 0;
    $rows.each(function()
    {
        var $row = $(this);
        if($row.find('td.c-id').length == 0) return;

        var data = $row.data();
        if(data.type === 'normal') normal++;
        if(data.type === 'terminate') terminate++;
        total++;
    });

    var summary = '';
    if(type != 'all')
    {
        summary =  pageSummary.replace('%s', total);
    }
    else
    {
        summary = pageAllSummary.replace('%total%', total).replace('%normal%', normal).replace('%terminate%', terminate);
    }
    $('.table-statistic').html(summary);
});
