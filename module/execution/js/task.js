$(function()
{
    adjustTableFooter();

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();

    $('.table-footer .check-all').on('click', function()
    {
        var $dtable = zui.DTable.query('#taskList').$;
        if($(this).hasClass('checked'))
        {
            $(this).removeClass('checked');
            $('.has-checkbox').click().removeClass('is-checked');
            $('.dtable-checkbox').removeClass('checked');
            $dtable.toggleCheckRows(false);
        }
        else
        {
            $(this).addClass('checked');
            $('.has-checkbox').click().addClass('is-checked');
            $('.dtable-checkbox').addClass('checked');
            $dtable.toggleCheckRows(true);
        }

        setStatistics();
    })

    $('#executionTaskForm').on('click', '[data-form-action]', function() {
        $('#executionTaskForm').attr('action', $(this).data('formAction')).submit();
    });
});

$('#module' + moduleID).closest('li').addClass('active');
$('#product' + productID).closest('li').addClass('active');

/**
 * Adjust the table footer style.
 *
 * @access public
 * @return void
 */
function adjustTableFooter()
{
    if($('.main-col').height() < $(window).height())
    {
        $('.table.with-footer-fixed').css('margin-bottom', '0');
        $('.table-footer').removeClass('fixed-footer');
        $('.table-footer').css({'left': 0, 'bottom': 0, 'width': 'unset'});
    }
}

function createSortLink(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{orderBy}', sort);
}

function setStatistics()
{
    $('input[name^=taskIDList]').remove();

    var element = zui.DTable.query('#taskList').$;
    var checkedIDList = element.getChecks();
    if(checkedIDList.length > 0)
    {
        $('.table-footer .table-actions').show();
        if(element.isAllRowChecked()) $('.table-footer .check-all').addClass('checked');
    }
    else
    {
        $('.table-footer .check-all').removeClass('checked');
        $('.table-footer .table-actions').hide();
    }

    if(checkedIDList.length == 0) return $('.table-statistic').html(pageSummary);

    let totalLeft     = 0;
    let totalEstimate = 0;
    let totalConsumed = 0;

    let waitCount  = 0;
    let doingCount = 0;
    let totalCount = 0;
    $.each(checkedIDList, function(index, id)
    {
        if(element.getRowInfo(id) == undefined) return true;

        const task = element.getRowInfo(id).data;

        totalEstimate += Number(task.estimateNum);
        totalConsumed += Number(task.consumedNum);
        if(task.statusCode != 'cancel' && task.statusCode != 'closed') totalLeft += Number(task.leftNum);

        if(task.statusCode == 'wait')
        {
            waitCount ++;
        }
        else if(task.statusCode == 'doing')
        {
            doingCount ++;
        }

        totalCount ++;

        $('#executionTaskForm').append('<input type="hidden" name="taskIDList[]" value="' + id + '">');
    })

    $('.table-statistic').html(checkedSummary.replace('%total%', totalCount)
        .replace('%wait%', waitCount)
        .replace('%doing%', doingCount)
        .replace('%estimate%', totalEstimate.toFixed(1))
        .replace('%consumed%', totalConsumed.toFixed(1))
        .replace('%left%', totalLeft.toFixed(1))
    );
}

cols = JSON.parse(cols);
data = JSON.parse(data);
const options =
{
    striped: true,
    plugins: ['nested', 'checkable'],
    checkOnClickRow: true,
    sortLink: createSortLink,
    cols: cols,
    data: data,
    footer: false,
    responsive: true,
    onCheckChange: setStatistics,
    height: function(height)
    {
        return Math.min($(window).height() - $('#header').outerHeight() - $('#mainMenu').outerHeight() - $('.table-footer').outerHeight() - 30, height);
    },
    checkInfo: function(checkedIDList)
    {
        return setStatistics(this, checkedIDList);
    }
};
$('#taskList').dtable(options);
