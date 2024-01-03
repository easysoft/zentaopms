$(function()
{
    if(typeof(rawModule) == 'undefined') rawModule = 'product';

    $('#navbar .nav li').removeClass('active');
    $("#navbar .nav li[data-id=" + storyType + ']').addClass('active');

    if(vision != 'lite' && rawModule == 'projectstory' && URAndSR)
    {
        $('#navbar .nav>li[data-id=story]').addClass('active');
        $('#navbar .nav>li[data-id=story]>a').html($('.active [data-id=' + storyType + ']').text() + '<span class="caret"></span>');
    }

    if(storyType == 'requirement') $('#importAction').parent().hide();

    $('.table-footer .check-all').on('click', function()
    {
        var $dtable = zui.DTable.query('#storyList').$;
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
    });

    $('.dtable-cell.has-checkbox[data-row="HEADER"] .dtable-checkbox').click(function()
    {
        $('.table-footer .check-all').toggleClass('checked', !$('.table-footer .check-all').hasClass('checked'));
    });

    $('#toTaskButton').on('click', function()
    {
        var planID = $('#plan').val();
        if(planID)
        {
            parent.location.href = createLink('projectstory', 'importPlanStories', 'projectID=' + projectID + '&planID=' + planID + '&productID=' + productID);
        }
    })

    // Fix state dropdown menu position
    $('.c-stage > .dropdown').each(function()
    {
        var $this = $(this);
        var menuHeight = $(this).find('.dropdown-menu').outerHeight();
        var $tr = $this.closest('tr');
        var height = 0;
        while(height < menuHeight)
        {
            var $next = $tr.next('tr');
            if(!$next.length) break;
            height += $next.outerHeight;
        }
        if(height < menuHeight) $this.addClass('dropup');
    });

    /* Get checked stories. */
    $('#importToLib').on('click', function()
    {
        var element = zui.DTable.query('#storyList').$;
        var checkedIDList = element.getChecks();
        var storyIdList = [];

        $.each(checkedIDList, function(index, id){storyIdList.push(id);});
        $('#storyIdList').val(storyIdList.join(','));
    });

    $('#reviewItem ~ ul > li').on('click', function()
    {
        var element = zui.DTable.query('#storyList').$;
        var checkedIDList = element.getChecks();
        var storyString     = '';
        var reviewStoryTips = '';

        if(checkedIDList.length == 0) return;
        $.each(checkedIDList, function(index, id)
        {
            var getStoryReview = createLink('product', 'ajaxGetReviewers', "productID=" + productID + "&storyID=" + id);

            $.ajaxSettings.async = false;
            $.get(getStoryReview, function(data)
            {
                var reviewer = [];
                $(data).find('option:selected').each(function()
                {
                    reviewer.push($(this).val());
                });

                if($.inArray(account, reviewer) == -1) storyString += ' #' + storyValue;
            });
            $.ajaxSettings.async = true;
        });

        reviewStoryTips = reviewStory.replace("%s", storyString);
        if(storyString !== '') alert(reviewStoryTips);
    });

    $('#batchUnlinkStory').click(function()
    {
        var storyIdList = [];
        $("#productStoryForm input[name^='storyIdList']").each(function()
        {
            storyIdList.push($(this).val());
        });

        $.get(createLink('projectstory', 'batchUnlinkStory', 'projectID=' + projectID + '&storyIdList=' + storyIdList.join(',')), function(data)
        {
            if(data)
            {
                $('#batchUnlinkStoryTip tbody').html(data);
                $('#batchUnlinkStoryTip').modal({show: true});
            }
            else
            {
                window.location.reload();
            }
        });
    });

    $('#batchUnlinkStoryTip .close, #confirmBtn').click(function()
    {
        window.location.reload();
    })

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();

    $('#productStoryForm').on('click', '[data-form-action]', function()
    {
        $('#productStoryForm').attr('action', $(this).data('formAction')).submit();
    });
});

function createSortLink(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';
    return sortLink.replace('{orderBy}', sort);
}

function setStatistics()
{
    $('#productStoryForm').find('input[name^=storyIdList]').remove();

    var element = zui.DTable.query('#storyList').$;
    var checkedIDList = element.getChecks();
    $('.table-footer .table-actions').toggle(checkedIDList.length > 0);

    if(checkedIDList.length == 0) return $('.table-footer .table-statistic').html(pageSummary);

    let checkedTotal    = checkedIDList.length;
    let checkedEstimate = 0;
    let checkedCase     = 0;
    let rateCount       = checkedTotal;
    $.each(checkedIDList, function(index, id)
    {
        if(element.getRowInfo(id) == undefined) return true;

        const story = element.getRowInfo(id).data;

        checkedEstimate += parseFloat(story.estimateNum);
        if(story.caseCountNum > 0) checkedCase += 1;
        if(story.isParent) rateCount -= 1;

        if(id.indexOf('-') > 0) id = id.split('-')[1];
        $('#productStoryForm').append('<input type="hidden" name="storyIdList[]" value="' + id + '">');
    });

    var rate = '0%';
    if(rateCount) rate = Math.round(checkedCase / rateCount * 10000 / 100) + '' + '%';

    $('.table-footer .table-statistic').html(checkedSummary.replace('%total%', checkedTotal)
      .replace('%estimate%', checkedEstimate.toFixed(1))
      .replace('%rate%', rate));
}

cols = JSON.parse(cols);
data = JSON.parse(data).map(function(row)
{
    row.key = row.parent > 0 ? (row.parent + '-' + row.id) : row.id;
    return row;
});
const options =
{
    striped: true,
    plugins: ['nested', 'checkable'],
    checkOnClickRow: true,
    sortLink: createSortLink,
    cols: cols,
    data: data,
    rowKey: 'key',
    footer: false,
    responsive: true,
    onCheckChange: setStatistics,
    height: function(height)
    {
        return Math.min($(window).height() - $('#header').outerHeight() - $('#mainMenu').outerHeight() - $('.table-footer').outerHeight() - 30, height);
    },
    checkInfo: function(checkedIDList)
    {
        return setStatistics();
    }
};
$('#storyList').dtable(options);
