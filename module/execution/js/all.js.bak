$(function()
{
    $("#" + status + "Tab").addClass('btn-active-text');
    $(document).on('click', '.plan-toggle', function(e)
    {
        var id          = $(this).data('id');
        var $toggle     = $(this);
        var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
        $toggle.closest('#executionsForm').find('tr.parent-' + id).toggle(!isCollapsed);

        e.stopPropagation();
        e.preventDefault();
    });

    if($('#executionList thead th.c-name').width() < 260) $('#executionList thead th.c-name').width(260);

    $('#executionTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('execution', 'updateOrder'), {'executions' : list, 'orderBy' : orderBy});
    });

    var nameWidth = $('#executionsForm thead th.c-name').width();
    if(isCNLang && nameWidth < 150 && !useDatatable) $('#executionsForm thead th.c-name').css('width', '150px');
    if(!isCNLang && nameWidth < 200 && !useDatatable) $('#executionsForm thead th.c-name').css('width', '200px');

    toggleFold('#executionsForm', unfoldExecutions, 0, 'execution');

    $('.table td.has-child > .plan-toggle').each(function()
    {
        var fold = $(this).hasClass('collapsed');
        var parentID = $(this).closest('tr').attr('data-id');
        if(fold)
        {
            $('.parent-' + parentID).hide();
            $(this).closest('td').removeClass('parent');
        }
        else
        {
            $('.parent-' + parentID).show();
            $(this).closest('td').addClass('parent');
        }
    });

    /* Expand and fold substages. */
    $('.table td.has-child > .plan-toggle').click(function()
    {
        var parentID = $(this).closest('tr').attr('data-id');
        $('.parent-' + parentID).toggle();

        if($('.parent-' + parentID).css('display') == 'none')
        {
            $(this).closest('td').removeClass('parent');
        }
        else
        {
            $(this).closest('td').addClass('parent');
        }
    });

    $(document).on('click', "#toggleFold", function()
    {
        var fold = $(this).hasClass('collapsed');
        if(fold)
        {
            $('.has-child.c-name.flex').removeClass('parent');
            $('.table td.has-child > .plan-toggle').addClass('collapsed');
        }
        else
        {
            $('.has-child.c-name.flex').addClass('parent');
            $('.table td.has-child > .plan-toggle').removeClass('collapsed');
        }
    });

    /* Update table summary text. */
    $('#executionsForm').table(
    {
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $checkedRows = $table.find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            var $rows        = checkedTotal ? $checkedRows : $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var checkedWait     = 0;
            var checkedDoing    = 0;
            var executionIDList = [];
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable) $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');

                var data = $row.data();
                executionIDList.push(data.id);

                if(data.status === 'wait') checkedWait++;
                if(data.status === 'doing') checkedDoing++;
            });

            if(status != 'all') return (checkedTotal ? checkedExecutions : executionSummary).replace('%s', $rows.length);
            return (checkedTotal ? checkedSummary : pageSummary).replace('%total%', $rows.length).replace('%wait%', checkedWait).replace('%doing%', checkedDoing);
        }
    })

    $('input[name^="showEdit"]').click(function()
    {
        $.cookie('showExecutionBatchEdit', $(this).is(':checked') ? 1 : 0, {expires: config.cookieLife, path: config.webRoot});
        setCheckbox();
    });
    setCheckbox();

    $('#executionTableList tr').on('click', function(e)
    {
        if($.cookie('showExecutionBatchEdit') != 1) e.stopPropagation();
    });
});

/**
 * Location to product list.
 *
 * @param  int    productID
 * @param  int    projectID
 * @param  string status
 * @access public
 * @return void
 */
function byProduct(productID, projectID, status)
{
    location.href = createLink('project', 'all', "status=" + status + "&project=" + projectID + "&orderBy=" + orderBy + '&productID=' + productID);
}

/**
 * Set batch edit checkbox.
 *
 * @access public
 * @return void
 */
function setCheckbox()
{
    $('#executionsForm .checkbox-primary').hide();
    $('.check-all, .sortable tr').removeClass('checked');
    $(":checkbox[name^='executionIDList']").prop('checked', false);
    $('#executionsForm').removeClass('has-row-checked');
    if($.cookie('showExecutionBatchEdit') == 1) $('#executionsForm .checkbox-primary').show();
}
