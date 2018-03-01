$(function()
{
    ajaxGetSearchForm();

    $('#storyList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i]).attr('data-id') + ',';
        $.post(createLink('project', 'storySort', 'projectID=' + projectID), {'storys' : list, 'orderBy' : orderBy}, function()
        {
            var $target = $(data.element[0]);
            $target.hide();
            $target.fadeIn(1000);
            order = 'order_asc'
            history.pushState({}, 0, createLink('project', 'story', "projectID=" + projectID + '&orderBy=' + order));
        });
    });

    fixedTfootAction('#projectStoryForm');
    fixedTheadOfList('#storyList');

    $('#module' + moduleID).addClass('active');
    $('#product' + productID).addClass('active');
    $('#branch' + branchID).addClass('active');
    $(document).on('click', "#storyList tbody tr", function(){showCheckedSummary();});
    $(document).on('change', "#storyList :checkbox", function(){showCheckedSummary();});

    $("a[data-toggle='linkStoryByPlan']").click(function(){$('#linkStoryByPlan').modal('show')})

    $('#toTaskButton').on('click', function ()
    {
        var planID = $('#plan').val();
        if(planID)
        {
            parent.location.href = createLink('project', 'importPlanStories', 'projectID=' + projectID + '&planID=' + planID);
        }
    })
});

function showCheckedSummary()
{
    var $summary = $('tfoot .table-actions .text:last');
    if(!$summary.hasClass('readed'))
    {
        taskSummary = $summary.html();
        $summary.addClass('readed');
    }

    var checkedTotal    = 0;
    var checkedEstimate = 0;
    var checkedCase     = 0;
    $('[name^="storyIDList"]').each(function()
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
        $('tfoot .table-actions .text:last').html(summary);
    }
    else
    {
        $('tfoot .table-actions .text:last').html(taskSummary);
    }
}
