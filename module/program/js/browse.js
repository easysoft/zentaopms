$(function()
{
    $('input#editProject1').click(function()
    {
        var editProject = $(this).is(':checked') ? 1 : 0;
        $.cookie('editProject', editProject, {expires:config.cookieLife, path:config.webRoot});
    });

    $(document).on('click', ":checkbox[name^='projectIdList']", function()
    {
        var notCheckedLength = $(":checkbox[name^='projectIdList']:not(:checked)").length;
        var checkedLength    = $(":checkbox[name^='projectIdList']:checked").length;

        if(checkedLength > 0) $('#programForm').addClass('has-row-checked');
        if(notCheckedLength == 0) $('.table-footer #checkAll').prop('checked', true);
        if(checkedLength == 0)
        {
            $('.table-footer #checkAll').prop('checked', false);
            $('#programForm').removeClass('has-row-checked');
        }

        var summary = checkedProjects.replace('%s', checkedLength);
        if(cilentLang == "en" && checkedLength < 2) summary = summary.replace('items', 'item');
        var statistic = "<div id='projectsSummary' class='table-statistic'>" + summary + "</div>";
        if(checkedLength > 0)
        {
            $('#programSummary').addClass('hidden');
            $('#projectsSummary').remove();
            $('.editCheckbox').after(statistic);
        }
        else
        {
            $('#programSummary').removeClass('hidden');
            $('#projectsSummary').addClass('hidden');
        }

    });

    $(document).on('click', ".table-footer #checkAll", function()
    {
        if($(this).prop('checked'))
        {
            $(":checkbox[name^='projectIdList']").prop('checked', true);
            $('#programForm').addClass('has-row-checked');
            var checkedLength = $(":checkbox[name^='projectIdList']:checked").length;
            var summary = checkedProjects.replace('%s', checkedLength);
            if(cilentLang == "en" && checkedLength < 2) summary = summary.replace('items', 'item');
            var statistic = "<div id='projectsSummary' class='table-statistic'>" + summary + "</div>";
            $('#programSummary').addClass('hidden');
            $('#projectsSummary').remove();
            $('.editCheckbox').after(statistic);
            $(this).next('label').addClass('hover');
        }
        else
        {
            $(":checkbox[name^='projectIdList']").prop('checked', false);
            $('#programForm').removeClass('has-row-checked');
            $('#programSummary').removeClass('hidden');
            $('#projectsSummary').addClass('hidden');
            $(this).next('label').removeClass('hover');
        }
    });

    /* Solve the problem that clicking the browser back button causes the checkbox to be selected by default. */
    setTimeout(function()
    {
        $(":checkbox[name^='projectIdList']").each(function()
        {
            $(this).prop('checked', false);
        });
        $('.table-footer #checkAll').prop('checked', false);
    }, 10);
});

function showEditCheckbox(show)
{
    $('.icon-project,.icon-waterfall,.icon-scrum,.icon-kanban,.icon-agileplus,.icon-waterfallplus,.icon-ipd').each(function()
    {
        $this     = $(this);
        $tr       = $(this).closest('tr');
        projectID = $tr.attr('data-id');
        if(show)
        {
            var marginLeft = $tr.find('td:first').find('span.table-nest-icon').css('margin-left');

            $tr.find('td:first').prepend("<div class='checkbox-primary'><input type='checkbox' name='projectIdList[]' value='" + projectID + "' id='projectIdList" + projectID + "'/><label for='projectIdList" + projectID + "'></lable></div>");
            $tr.find('td:first').find('.checkbox-primary').css('margin-left', marginLeft).css('width', '14');
            $tr.find('td:first').find('span.table-nest-icon').css('margin-left', '0');
        }
        else
        {
            var marginLeft = $tr.find('td:first').find('.checkbox-primary').css('margin-left');
            $tr.find('td:first').find('span.table-nest-icon').css('margin-left', marginLeft);
            $tr.find('td:first').find('[name^="projectIdList"]').parent().remove();
        }
    });
    if(show && hasProject)
    {
        var tableFooter = "<div class='editCheckbox'><div class='checkbox-primary check-all'><input type='checkbox' id='checkAll' /><label>" + selectAll + "</label></div><div class='table-actions btn-toolbar'><button type='submit' class='btn'>" + edit + "</button></div></div>";
        $('#programForm').attr('action', createLink('project', 'batchEdit', 'from=program'));
        $('.table-footer').prepend(tableFooter).show();
        $('body').scroll();
    }
    else
    {
        $('#programForm').removeClass('has-row-checked');
        $('#projectsSummary').addClass('hidden');
        $('#programSummary').removeClass('hidden');
        $('#programForm').find('.editCheckbox').remove();
        if($('#programForm .pager').length == 0) $('.table-footer').hide();
        $('#programForm').removeAttr('action');
    }
}
