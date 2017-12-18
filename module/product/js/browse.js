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

function setQueryBar(queryID, title)
{
    $('#bysearchTab').before("<li id='QUERY" + queryID + "Tab' class='active'><a href='" + createLink('product', 'browse', "productID=" + productID + "&branch=" + branch + "&browseType=bysearch&param=" + queryID) + "'>" + title + "</a></li>");
}
