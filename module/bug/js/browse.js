$(function()
{
    setTitleWidth();

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();

    $('#bugList').on('change', "[name='bugIDList[]']", checkClosed);

    $('.table-footer .check-all').on('click', function()
    {
        var $dtable = zui.DTable.query('#bugList').$;
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
});

/**
 * Closed bugs are not assignable.
 *
 * @access public
 * @return void
 */
function checkClosed()
{
    var disabledAssigned = $('#bugList tr.checked .c-assignedTo a').length > 0 ? true : false;
    $('#bugList tr.checked .c-assignedTo a').each(function()
    {
        if(!$(this).hasClass('disabled'))
        {
            disabledAssigned = false;
            return false;
        }
    });

    $('#mulAssigned').prop('disabled', disabledAssigned);
}

function setStatistics()
{
    $('input[name^=bugIDList]').remove();

    const element      = zui.DTable.query('#bugList').$;
    const checkedList  = element.getChecks();
    const checkedCount = checkedList.length;
    const allChecked   = element.isAllRowChecked();
    $('.table-footer .table-actions').toggle(checkedCount > 0);
    $('.table-footer .check-all').toggleClass('checked', allChecked);
    if(checkedCount == 0) return $('.table-statistic').html(pageSummary);

    checkedList.forEach(function(id)
    {
        if(element.getRowInfo(id) == undefined) return true;

        $('#bugForm').append('<input type="hidden" name="bugIDList[]" value="' + id + '">');
    });

    $('.table-statistic').html(checkedSummary.replace('{0}', checkedCount));
}

function createSortLink(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{orderBy}', sort);
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
    fixedLeftWidth: 550,
    fixedRightWidth: 150,
    height: function(height)
    {
        return Math.min($(window).height() - $('#header').outerHeight() - $('#mainMenu').outerHeight() - $('.table-footer').outerHeight() - 30, height);
    },
    checkInfo: function(checkedIDList)
    {
        return setStatistics(this, checkedIDList);
    }
};
$('#bugList').dtable(options);
