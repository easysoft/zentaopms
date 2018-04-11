$(function()
{
    if(browseType == 'bysearch') ajaxGetSearchForm();

    $('.dropdown-menu.with-search .menu-search').click(function(e)
    {
        e.stopPropagation();
        return false;
    }).on('keyup change paste', 'input', function()
    {
        var $input = $(this);
        var val = $input.val().toLowerCase();
        var $options = $input.closest('.dropdown-menu.with-search').find('.option');
        if(val == '') return $options.removeClass('hide');
        $options.each(function()
        {
            var $option = $(this);
            $option.toggleClass('hide', $option.text().toString().toLowerCase().indexOf(val) < 0 && $option.data('key').toString().toLowerCase().indexOf(val) < 0);
        });
    });

    $('.popoverStage').mouseover(function(){$(this).popover('show')});
    $('.popoverStage').mouseout(function(){$(this).popover('hide')});
    setTimeout(function(){fixedTfootAction('#productStoryForm')}, 100);
    setTimeout(function(){fixedTheadOfList('#storyList')}, 100);

    if($('#storyList thead th.w-title').width() < 150) $('#storyList thead th.w-title').width(150);

    $(document).on('click',  "#storyList tbody tr", function(){showCheckedSummary();});
    $(document).on('change', "#storyList :checkbox", function(){showCheckedSummary();});
    $(document).on('click',  "#datatable-storyList table tr", function(){showCheckedSummary();});
})

function showCheckedSummary()
{
    var $summary = $('#main #mainContent form.main-table .table-header .table-statistic');
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
        $summary.html(summary);
    }
    else
    {
        $summary.html(taskSummary);
    }
}

function setQueryBar(queryID, title)
{
    $('#bysearchTab').before("<a id='QUERY" + queryID + "Tab' class='btn btn-link btn-active-text' href='" + createLink('product', 'browse', "productID=" + productID + "&branch=" + branch + "&browseType=bysearch&param=" + queryID) + "'><span class='text'>" + title + "</span></a>");
}
