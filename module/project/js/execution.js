$(function()
{
    $('input[name^="showTask"]').click(function()
    {
        var show = $(this).is(':checked') ? 1 : 0;
        $.cookie('showTask', show, {expires:config.cookieLife, path:config.webRoot});
        if(show == 1) $('input#editExecution1').prop('disabled', true);
        if(show == 0) $('input#editExecution1').prop('disabled', false);
        window.location.reload();
    });
    if($.cookie('showTask') == 1) $('input#editExecution1').prop('disabled', true);

    $('input#editExecution1').click(function()
    {
        var editExecution = $(this).is(':checked') ? 1 : 0;
        $.cookie('editExecution', editExecution, {expires:config.cookieLife, path:config.webRoot});
        if(editExecution == 1) $('input[name^="showTask"]').prop('disabled', true);
        if(editExecution == 0) $('input[name^="showTask"]').prop('disabled', false);

        showEditCheckbox(editExecution);
    });
    if($.cookie('editExecution') == 1)
    {
      $('input#editExecution1').prop('checked', 'true');
      showEditCheckbox(true);
      $('input[name^="showTask"]').prop('disabled', true);
    }

    $(document).on('click', ":checkbox[name^='executionIDList']", function()
    {
        var notCheckedLength = $(":checkbox[name^='executionIDList']:not(:checked)").length;
        var checkedLength    = $(":checkbox[name^='executionIDList']:checked").length;

        if(checkedLength > 0) $('#executionForm').addClass('has-row-checked');
        if(notCheckedLength == 0) $('.table-footer #checkAll').prop('checked', true);
        if(checkedLength == 0)
        {
            $('.table-footer #checkAll').prop('checked', false);
            $('#executionForm').removeClass('has-row-checked');
        }

        var summary = checkedExecutions.replace('%s', checkedLength);
        if(cilentLang == "en" && checkedLength < 2) summary = summary.replace('items', 'item');
        var statistic = "<div id='executionsSummary' class='table-statistic'>" + summary + "</div>";
        if(checkedLength > 0)
        {
            $('#executionSummary').addClass('hidden');
            $('#executionsSummary').remove();
            $('.editCheckbox').after(statistic);
        }
        else
        {
            $('#executionSummary').removeClass('hidden');
            $('#executionsSummary').addClass('hidden');
        }

    });

    $(document).on('click', ".table-footer #checkAll", function()
    {
        if($(this).prop('checked'))
        {
            $(":checkbox[name^='executionIDList']").prop('checked', true);
            $('#executionForm').addClass('has-row-checked');
            var checkedLength = $(":checkbox[name^='executionIDList']:checked").length;
            var summary = checkedExecutions.replace('%s', checkedLength);
            if(cilentLang == "en" && checkedLength < 2) summary = summary.replace('items', 'item');
            var statistic = "<div id='executionsSummary' class='table-statistic'>" + summary + "</div>";
            $('#executionSummary').addClass('hidden');
            $('#executionsSummary').remove();
            $('.editCheckbox').after(statistic);
            $(this).next('label').addClass('hover');
        }
        else
        {
            $(":checkbox[name^='executionIDList']").prop('checked', false);
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
        $('.table-footer #checkAll').prop('checked', false);
    }, 10);
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

function showEditCheckbox(show)
{
    $('.project-type-label').each(function()
    {
        $this     = $(this);
        $tr       = $(this).closest('tr');
        executionID = $tr.attr('data-id');
        if(show)
        {
            var marginLeft = $tr.find('td:first').find('span.table-nest-icon').css('margin-left');

            $tr.find('td:first').prepend("<div class='checkbox-primary'><input type='checkbox' name='executionIDList[]' value='" + executionID + "' id='executionIDList" + executionID + "'/><label for='executionIDList" + executionID + "'></lable></div>");
            $tr.find('td:first').find('.checkbox-primary').css('margin-left', marginLeft).css('width', '14');
            $tr.find('td:first').find('span.table-nest-icon').css('margin-left', '0');
        }
        else
        {
            var marginLeft = $tr.find('td:first').find('.checkbox-primary').css('margin-left');
            $tr.find('td:first').find('span.table-nest-icon').css('margin-left', marginLeft);
            $tr.find('td:first').find('[name^="executionIDList"]').parent().remove();
        }
    });
    if(show)
    {
        var tableFooter = "<div class='editCheckbox'><div class='checkbox-primary check-all'><input type='checkbox' id='checkAll' /><label>" + selectAll + "</label></div><div class='table-actions btn-toolbar'><button type='submit' class='btn'>" + edit + "</button></div></div>";
        $('#executionForm').attr('action', createLink('execution', 'batchEdit'));
        $('.table-footer').prepend(tableFooter).show();
        $('body').scroll();
    }
    else
    {
        $('#executionForm').find('.editCheckbox').remove();
        if($('#executionForm .pager').length == 0) $('.table-footer').hide();
        $('#executionForm').removeAttr('action');
    }
}
