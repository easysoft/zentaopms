$(function()
{
    $('input[name^="showClosed"]').click(function()
    {
        var showClosed = $(this).is(':checked') ? 1 : 0;
        $.cookie('showClosed', showClosed, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    $('input#editProject1').click(function()
    {
        var editProject = $(this).is(':checked') ? 1 : 0;
        $.cookie('editProject', editProject, {expires:config.cookieLife, path:config.webRoot});
        showEditCheckbox(editProject);
    });
    if($.cookie('editProject') == 1) $('input#editProject1').prop('checked', 'true');
    if($('input#editProject1').prop('checked')) showEditCheckbox(true);

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
    });

    $(document).on('click', ".table-footer #checkAll", function()
    {
        if($(this).prop('checked'))
        {
            $(":checkbox[name^='projectIdList']").prop('checked', true);
            $('#programForm').addClass('has-row-checked');
        }
        else
        {
            $(":checkbox[name^='projectIdList']").prop('checked', false);
            $('#programForm').removeClass('has-row-checked');
        }
    });
});

function showEditCheckbox(show)
{
    $('.icon-project,.icon-waterfall,.icon-scrum,.icon-kanban').each(function()
    {
        $this     = $(this);
        $tr       = $(this).closest('tr');
        projectID = $tr.attr('data-id');
        if(show)
        {
            $tr.find('td:first').prepend("<div class='checkbox-primary'><input type='checkbox' name='projectIdList[]' value='" + projectID + "' id='projectIdList" + projectID + "'/><label for='projectIdList" + projectID + "'></lable></div>");

            var marginLeft = $tr.find('td:first').find('span.table-nest-icon').css('margin-left');
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
        $('#programForm').find('.editCheckbox').remove();
        if($('#programForm .pager').length == 0) $('.table-footer').hide();
        $('#programForm').removeAttr('action');
    }
}
