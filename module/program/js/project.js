$(function()
{
    $('#projectsForm').table(
    {
        replaceId: 'projectIdList',
        statisticCreator: function(table)
        {
            var $table            = table.getTable();
            var $checkedRows      = $table.find('tbody>tr.checked');
            var checkedTotal      = $checkedRows.length;
            var statistics        = summary;
            var checkedStatistics = checkedSummary.replace('%total%', checkedTotal);

            if(browseType == 'all')
            {
                var checkedWait      = $checkedRows.filter("[data-status=wait]").length;
                var checkedDoing     = $checkedRows.filter("[data-status=doing]").length;
                var checkedSuspended = $checkedRows.filter("[data-status=suspended]").length;
                var checkedClosed    = $checkedRows.filter("[data-status=closed]").length;

                statistics        = allSummary;
                checkedStatistics = checkedAllSummary.replace('%total%', checkedTotal)
                    .replace('%wait%', checkedWait)
                    .replace('%doing%', checkedDoing)
                    .replace('%suspended%', checkedSuspended)
                    .replace('%closed%', checkedClosed);
            }

            return checkedTotal ? checkedStatistics : statistics;
        }
    });

    $('input[name^="showEdit"]').click(function()
    {
        $.cookie('showProjectBatchEdit', $(this).is(':checked') ? 1 : 0, {expires: config.cookieLife, path: config.webRoot});
        setCheckbox();
    });
    setCheckbox();

    $(":checkbox[name^='projectIdList']").on('click', function()
    {
        updateStatistic()
    });

    $(".check-all").on('click', function()
    {
        if($(":checkbox[name^='projectIdList']:not(:checked)").length == 0)
        {
            $(":checkbox[name^='projectIdList']").prop('checked', false);
        }
        else
        {
            $(":checkbox[name^='projectIdList']").prop('checked', true);
        }
        updateStatistic()
    });

    $('.main-table').on('click', 'tr', function(e)
    {
        if($.cookie('showProjectBatchEdit') == 1) updateStatistic();
    })
});

/**
 * Set batch edit checkbox.
 *
 * @access public
 * @return void
 */
function setCheckbox()
{
    $('#projectsForm .checkbox-primary').hide();
    $(":checkbox[name^='projectIdList']").prop('checked', false);
    $('.check-all, .sortable tr').removeClass('checked');
    if($.cookie('showProjectBatchEdit') == 1)
    {
        $('#projectsForm .checkbox-primary').show();
    }
    else
    {
        $('.table-actions').hide();
    }
}

/**
 * Add a statistics prompt statement after the Edit button.
 *
 * @access public
 * @return void
 */
function addStatistic()
{
    var checkedLength = $(":checkbox[name^='projectIdList']:checked").length;
    if(checkedLength > 0)
    {
        $('.table-actions').show();
    }
    else
    {
        $('.table-actions').hide();
    }
}

/**
 * Anti shake operation for jquery.
 *
 * @param  fn $fn
 * @param  delay $delay
 * @access public
 * @return void
 */
function debounce(fn, delay)
{
    var timer = null;
    return function()
    {
        if(timer) clearTimeout(timer);
        timer = setTimeout(fn, delay)
    }
}

/**
 * Update statistics.
 *
 * @access public
 * @return void
 */
function updateStatistic()
{
    debounce(addStatistic(), 200)
}
