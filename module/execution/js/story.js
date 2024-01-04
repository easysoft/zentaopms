$(function()
{
    if($('#storyList thead th.c-title').width() < 150) $('#storyList thead th.c-title').width(150);

    if(isDropMenu)
    {
        $('#navbar .nav li').removeClass('active');
        $("#navbar .nav li[data-id=" + storyType + ']').addClass('active');

        $('#navbar .nav>li[data-id=story]').addClass('active');
        $('#navbar .nav>li[data-id=story]>a').html($('.active [data-id=' + storyType + ']').text() + '<span class="caret"></span>');
    }

    $('#storyList td.has-child .story-toggle').each(function()
    {
        var $td = $(this).closest('td');
        var labelWidth = 0;
        if($td.find('.label').length > 0) labelWidth = $td.find('.label').width();
        $td.find('a').eq(0).css('max-width', $td.width() - labelWidth - 60);
    });

    $(document).on('click', '.story-toggle', function(e)
    {
        var $toggle = $(this);
        var id = $(this).data('id');
        var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
        $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

        e.stopPropagation();
        e.preventDefault();
    });

    $('#storyList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('execution', 'storySort', 'executionID=' + executionID), {'storys' : list, 'orderBy' : orderBy}, function()
        {
            var $target = $(data.element[0]);
            $target.hide();
            $target.fadeIn(1000);
            order = 'order_desc'
            history.pushState({}, 0, createLink('execution', 'story', "executionID=" + executionID + '&orderBy=' + order));
        });
    });

    $('#module' + moduleID).parent().addClass('active');
    $('#product' + productID).addClass('active');
    $('#branch' + branchID).addClass('active');
    $(document).on('click', "#storyList tbody tr, .table-footer .check-all, #storyList thead .check-all", function(){showCheckedSummary();});
    $(document).on('change', "#storyList :checkbox", function(){showCheckedSummary();});

    $('#toTaskButton').on('click', function()
    {
        var planID = $('#plan').val();
        if(planID)
        {
            location.href = createLink('execution', 'importPlanStories', 'executionID=' + executionID + '&planID=' + planID + '&productID=0&fromMethod=story&extra=&param=executionID=' + executionID + ',storyType=' + storyType);
        }
    })

    /* Get checked stories. */
    $(document).on('click', '#batchToTaskButton', function()
    {
        storyIdList      = '';
        linedTaskIdList  = '';
        unlinkTaskIdList = '';
        $("input[name^='storyIdList']:checked").each(function()
        {
            if(linkedTaskStories[$(this).val()])
            {
                linedTaskIdList += '[' + $(this).val() +']';
            }
            else
            {
                unlinkTaskIdList += $(this).val() + ',';
            }
            storyIdList += $(this).val() + ',';
        });

        $('#type').val('').trigger("chosen:updated");
        $('#hourPointValue').val('');
        $('input[name^=fields]').prop('checked', true);
    });

    $('#submit').click(function()
    {
        var taskType  = $('#type').val();
        var hourPoint = $('#hourPointValue').val();
        if(taskType.length == 0)
        {
            alert(typeNotEmpty);
            return false;
        }

        if(hourPoint == 0)
        {
            alert(hourPointNotEmpty);
            return false;
        }
        else if(typeof(hourPoint) != 'undefined' && (isNaN(hourPoint) || hourPoint < 0))
        {
            alert(hourPointNotError);
            return false;
        }
        hourPoint = typeof(hourPoint) == 'undefined' ? 0 : hourPoint;

        if(linedTaskIdList)
        {
            confirmStoryToTask = confirmStoryToTask.replace('%s', linedTaskIdList);
            if(confirm(confirmStoryToTask))
            {
                $('#storyIdList').val(storyIdList);
            }
            else
            {
                if(!unlinkTaskIdList) return false;

                $('#storyIdList').val(unlinkTaskIdList);
            }
        }
        else
        {
            $('#storyIdList').val(storyIdList);
        }
    });

    $('.sorter-false a').unwrap();

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();
});

/**
 * Show checked summary.
 *
 * @access public
 * @return void
 */
function showCheckedSummary()
{
    var $summary = $('#main #mainContent form.main-table .table-header .table-statistic');

    var checkedTotal    = 0;
    var checkedEstimate = 0;
    var checkedCase     = 0;
    $('[name^="storyIdList"]').each(function()
    {
        if($(this).prop('checked'))
        {
            checkedTotal += 1;
            var taskID = $(this).val();
            $tr = $("#storyList tbody tr[data-id='" + taskID + "']");
            checkedEstimate += Number($tr.data('estimate'));
            if(Number($tr.data('cases')) > 0) checkedCase += 1;
        }
    });
    if(checkedTotal > 0)
    {
        rate    = Math.round(checkedCase / checkedTotal * 10000) / 100 + '' + '%';
        summary = checkedSummary.replace('%total%', checkedTotal)
          .replace('%estimate%', checkedEstimate)
          .replace('%rate%', rate)
        $summary.html(summary);
    }
}
