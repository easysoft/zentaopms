$(function()
{
    if($('#taskList thead th.c-name').width() < 150) $('#taskList thead th.c-name').width(150);
    $('#taskList td.has-child .task-toggle').each(function()
    {
        var $td = $(this).closest('td');
        var labelWidth = 0;
        if($td.find('.label').length > 0) labelWidth = $td.find('.label').width();
        $td.find('a').eq(0).css('max-width', $td.width() - labelWidth - 60);
    });

    if($('#projectTaskForm td.has-child').length > 0)
    {
        $('#projectTaskForm th.c-name').append("<button type='button' id='toggleFold' class='btn btn-mini collapsed'>" + unfoldAll + "</button>");
        var allUnfold = true;
        $('#projectTaskForm td.has-child').each(function()
        {
            var taskID = $(this).closest('tr').attr('data-id');
            if(typeof(unfoldID[taskID]) == 'undefined')
            {
                allUnfold = false;
                $('#projectTaskForm tr.parent-' + taskID).hide();
                $(this).find('a.task-toggle').addClass('collapsed')
            }

        })

        if(allUnfold)
        {
            $('#projectTaskForm th.c-name #toggleFold').html(foldAll).removeClass('collapsed');
        }
        else
        {
            $('#projectTaskForm th.c-name #toggleFold').html(unfoldAll).addClass('collapsed');
        }

        $(document).on('click', '#toggleFold', function()
        {
            var newUnfoldID = [];
            var url         = '';
            if($(this).hasClass('collapsed'))
            {
                $('#projectTaskForm td.has-child').each(function()
                {
                    var taskID = $(this).closest('tr').attr('data-id');
                    $('#projectTaskForm tr.parent-' + taskID).show();
                    $(this).find('a.task-toggle').removeClass('collapsed')
                    newUnfoldID.push(taskID);
                })
                $(this).html(foldAll).removeClass('collapsed');
                url = createLink('project', 'ajaxSetUnfoldID', 'projectID=' + projectID);
            }
            else
            {
                $('#projectTaskForm td.has-child').each(function()
                {
                    var taskID = $(this).closest('tr').attr('data-id');
                    $('#projectTaskForm tr.parent-' + taskID).hide();
                    $(this).find('a.task-toggle').addClass('collapsed');
                    newUnfoldID.push(taskID);
                })
                $(this).html(unfoldAll).addClass('collapsed');
                url = createLink('project', 'ajaxSetUnfoldID', 'projectID=' + projectID + '&action=delete');
            }
            $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
        });

        $('#projectTaskForm td.has-child a.task-toggle').click(function()
        {
            var newUnfoldID = [];
            var url         = '';
            if($(this).hasClass('collapsed'))
            {
                var taskID = $(this).closest('tr').attr('data-id');
                $('#projectTaskForm tr.parent-' + taskID).show();
                newUnfoldID.push(taskID);
                url = createLink('project', 'ajaxSetUnfoldID', 'projectID=' + projectID);
            }
            else
            {
                var taskID = $(this).closest('tr').attr('data-id');
                $('#projectTaskForm tr.parent-' + taskID).hide();
                newUnfoldID.push(taskID);
                url = createLink('project', 'ajaxSetUnfoldID', 'projectID=' + projectID + '&action=delete');
            }

            setTimeout(function()
            {
                if($('#projectTaskForm td.has-child a.task-toggle.collapsed').length == 0)
                {
                    $('#toggleFold').html(foldAll).removeClass('collapsed');
                }
                else
                {
                    $('#toggleFold').html(unfoldAll).addClass('collapsed');
                }
            }, 100);

            $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
        });
    }
});

$('#module' + moduleID).closest('li').addClass('active');
$('#product' + productID).closest('li').addClass('active');
