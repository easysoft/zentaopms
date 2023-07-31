$(function()
{
    $("#" + status + "Tab").addClass('btn-active-text');

    $('input[name^="showTask"]').click(function()
    {
        var show = $(this).is(':checked') ? 1 : 0;
        $.cookie('showTask', show, {expires:config.cookieLife, path:config.webRoot});
        $('input#editExecution1').prop('disabled', show).attr('title', show == 1 ? disabledExecutionTip : defaultExecutionTip);
        window.location.reload();
    });
    $('input[name^="showStage"]').click(function()
    {
        var show = $(this).is(':checked') ? 1 : 0;
        $.cookie('showStage', show, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
    if($.cookie('showTask') == 1) $('input#editExecution1').prop('disabled', true).attr('title', disabledExecutionTip);

    $('input#editExecution1').click(function()
    {
        var editExecution = $(this).is(':checked') ? 1 : 0;
        $.cookie('editExecution', editExecution, {expires:config.cookieLife, path:config.webRoot});
        $('input[name^="showTask"]').prop('disabled', editExecution).attr('title', editExecution == 1 ? disabledTaskTip : defaultTaskTip);

        showEditCheckbox(editExecution);
    });
    if($.cookie('editExecution') == 1)
    {
      $('input#editExecution1').prop('checked', 'true');
      showEditCheckbox(true);
      $('input[name^="showTask"]').prop('disabled', true).attr('title', disabledTaskTip);
    }

    $(document).on('click', ":checkbox[name^='executionIDList']", function()
    {
        var notCheckedLength = $(":checkbox[name^='executionIDList']:not(:checked)").length;
        var checkedLength    = $(":checkbox[name^='executionIDList']:checked").length;

        if(checkedLength > 0) $('#executionForm').addClass('has-row-checked');
        if(notCheckedLength == 0) $('#executionForm .checkAll').prop('checked', true);
        if(checkedLength == 0)
        {
            $('#executionForm .checkAll').prop('checked', false);
            $('#executionForm').removeClass('has-row-checked');
        }
        if(notCheckedLength > 0) $('#executionForm .checkAll').prop('checked', false);

        if(checkedLength > 0)
        {
            $('#executionSummary').addClass('hidden');
            $('#executionsSummary').remove();
        }
        else
        {
            $('#executionSummary').removeClass('hidden');
            $('#executionsSummary').addClass('hidden');
        }

    });

    $(document).on('click', "#executionForm .checkAll", function()
    {
        if($(this).prop('checked'))
        {
            $(":checkbox[name^='executionIDList']").prop('checked', true);
            $("#executionForm .checkAll").prop('checked', true);
            $('#executionForm').addClass('has-row-checked');
            var checkedLength = $(":checkbox[name^='executionIDList']:checked").length;
            $('#executionSummary').addClass('hidden');
            $('#executionsSummary').remove();
            $(this).next('label').addClass('hover');
        }
        else
        {
            $(":checkbox[name^='executionIDList']").prop('checked', false);
            $("#executionForm .checkAll").prop('checked', false);
            $('#executionForm').removeClass('has-row-checked');
            $('#executionSummary').removeClass('hidden');
            $('#executionsSummary').addClass('hidden');
            $(this).next('label').removeClass('hover');
        }
    });

    /* Solve the problem that clicking the browser back button causes the checkbox to be selected by default. */
    setTimeout(function()
    {
        $(":checkbox[name^='executionIDList']").each(function()
        {
            $(this).prop('checked', false);
        });
        $('#executionsForm .checkAll').prop('checked', false);
    }, 10);

    /* Update table summary text. */
    $('#executionForm').table(
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
            var executionCount  = 0;
            var executionIDList = [];
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable) $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');

                var data = $row.data();
                executionIDList.push(data.id);

                if(data.status === 'wait') checkedWait++;
                if(data.status === 'doing') checkedDoing++;
                if('status' in data) executionCount++;
            });

            if(status != 'all') return (checkedTotal ? checkedExecutions : executionSummary).replace('%s', executionCount);
            return (checkedTotal ? checkedSummary : pageSummary).replace('%total%', executionCount).replace('%wait%', checkedWait).replace('%doing%', checkedDoing);
        }
    })

    if(project.division && project.hasProduct && $('#executionList thead th.table-nest-title').width() < 240)
    {
        $('#executionList thead th.table-nest-title').width(240)
    }
})

window.addEventListener('scroll', this.handleScroll)
function handleScroll(e)
{
    var relative = 200; // 相对距离
    $('tr.showmore').each(function()
    {
        var $showmore = $(this);
        var offsetTop = $showmore[0].offsetTop;
        if(offsetTop == 0) return true;

        if(getScrollTop() + getWindowHeight() >= offsetTop - relative)
        {
            throttle(loadData($showmore), 150)
        }
    })
}

function loadData($showmore)
{
    $showmore.removeClass('showmore');

    var executionID = $showmore.attr('data-parent');
    var maxTaskID   = $showmore.attr('data-id');
    var maxTaskID   = maxTaskID.replace('t', '');
    var link = createLink('task', 'ajaxGetTasks', 'executionID=' + executionID + '&maxTaskID=' + maxTaskID);
    $.get(link, function(data)
    {
        $showmore.after(data);
        $(".iframe").modalTrigger({type:'iframe'});

        $('#executionForm').table('initNestedList');
    })
}

function throttle(fn, threshhold)
{
    var last;
    var timer;
    threshhold || (threshhold = 250);

    return function()
    {
        var context = this;
        var args = arguments;

        var now = +new Date()

        if (last && now < last + threshhold)
        {
            clearTimeout(timer);
            timer = setTimeout(function ()
            {
                last = now
                fn.apply(context, args)
            }, threshhold)
        }
        else
        {
            last = now
            fn.apply(context, args)
        }
    }
}

function getScrollTop()
{
    return scrollTop = document.body.scrollTop + document.documentElement.scrollTop
}

function getWindowHeight()
{
    return document.compatMode == "CSS1Compat" ? windowHeight = document.documentElement.clientHeight : windowHeight = document.body.clientHeight
}

/**
 * Show edit executions checkbox.
 *
 * @param int $show
 * @access public
 * @return void
 */
function showEditCheckbox(show)
{
    $('.project-type-label').each(function()
    {
        $this       = $(this);
        $tr         = $(this).closest('tr');
        executionID = $tr.attr('data-id');
        if(show)
        {
            var marginLeft = '7px';

            $tr.find('td:first').prepend("<div class='checkbox-primary'><input type='checkbox' name='executionIDList[]' value='" + executionID + "' id='executionIDList" + executionID + "'/><label for='executionIDList" + executionID + "'></lable></div>");
            $tr.find('td:first').find('.checkbox-primary').css('margin-left', marginLeft).css('width', '14');
        }
        else
        {
            $tr.find('td:first').find('[name^="executionIDList"]').parent().remove();
        }
    });
    if(show)
    {
        $('.table-nest-title').prepend("<div class='checkbox-primary check-all'><input type='checkbox' class='checkAll' /><label></label></div>").addClass('table-nest-title-edit');
        var tableFooter = "<div class='editCheckbox'><div class='checkbox-primary check-all'><input type='checkbox' id='checkAll' class='checkAll' /><label>" + selectAll + "</label></div><div class='table-actions btn-toolbar'><button type='submit' class='btn'>" + edit + "</button>" + changeStatusHtml + "</div></div>";
        $('#executionForm').attr('action', createLink('execution', 'batchEdit'));
        $('.table-footer').prepend(tableFooter).show();
        $('body').scroll();
    }
    else
    {
        $('.table-nest-title').removeClass('table-nest-title-edit').find('.check-all').remove();
        $('#executionForm').find('.editCheckbox').remove();
        if($('#executionForm .pager').length == 0) $('.table-footer').hide();
        $('#executionForm').removeAttr('action');
    }
}

/**
 * Set the color of the badge to white.
 *
 * @param  object  obj
 * @param  bool    isShow
 * @access public
 * @return void
 */
function setBadgeStyle(obj, isShow)
{
    var $label = $(obj);
    if(isShow == true)
    {
        $label.find('.label-badge').css({"color":"#fff", "border-color":"#fff"});
    }
    else
    {
        $label.find('.label-badge').css({"color":"#838a9d", "border-color":"#838a9d"});
    }
}
