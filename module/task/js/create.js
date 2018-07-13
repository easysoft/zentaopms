/* Copy story title as task title. */
function copyStoryTitle()
{
    var storyTitle = $('#story option:selected').text();
    var startPosition = storyTitle.indexOf(':') + 1;
    if (startPosition > 0) {
        var endPosition   = storyTitle.lastIndexOf('(');
        storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);
    }

    $('#name').attr('value', storyTitle);
    $('#estimate').val($('#storyEstimate').val());
    $('#desc').val($('#storyDesc').val());

    $('.pri-text span:first').removeClass().addClass('pri' + $('#storyPri').val()).text($('#storyPri').val());
    $('select#pri').val($('#storyPri').val());

    $(window.editor.desc.edit.doc).find('span.kindeditor-ph').remove();
    window.editor.desc.html($('#storyDesc').val());
}

/* Set the assignedTos field. */
function setOwners(result)
{
    $("#multipleBox").removeAttr("checked");
    $('.team-group').addClass('hidden');
    $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
    if(result == 'affair')
    {
        $('#assignedTo').attr('multiple', 'multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen();
        $('.affair').hide();
        $('.team-group').addClass('hidden');
        $('#selectAllUser').removeClass('hidden');
    }
    else if($('#assignedTo').attr('multiple') == 'multiple')
    {
        $('#assignedTo').removeAttr('multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen();
        $('.affair').show();
        $('#selectAllUser').addClass('hidden');
    }
}

/* Set preview and module of story. */
function setStoryRelated()
{
    setPreview();
    setStoryModule();
}

/* Set the story module. */
function setStoryModule()
{
    var storyID = $('#story').val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            $('#module').val(storyInfo.moduleID);
            $("#module").trigger("chosen:updated");

            $('#storyEstimate').val(storyInfo.estimate);
            $('#storyPri'     ).val(storyInfo.pri);
            $('#storyDesc'    ).val(storyInfo.spec);
        });
    }
}

/* Set the story priview link. */
function setPreview()
{
    if(!$('#story').val())
    {
        $('#preview').addClass('hidden');
        $('#copyButton').addClass('hidden');
        $('div.colorpicker').css('right', '1px');//Adjust for task #4151;
    }
    else
    {
        storyLink  = createLink('story', 'view', "storyID=" + $('#story').val());
        var concat = config.requestType != 'GET' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview').removeClass('hidden');
        $('#preview a').attr('href', storyLink);
        $('#copyButton').removeClass('hidden');
        $('div.colorpicker').css('right', '57px');//Adjust for task #4151;
    }

    setAfter();
}

/**
 * Set after locate. 
 * 
 * @access public
 * @return void
 */
function setAfter()
{
    if($("#story").length == 0 || $("#story").select().val() == '') 
    {
        if($('input[value="continueAdding"]').attr('checked') == 'checked') 
        {
            $('input[value="toTaskList"]').attr('checked', 'checked');
        }
        $('input[value="continueAdding"]').attr('disabled', 'disabled');
        $('input[value="toStoryList"]').attr('disabled', 'disabled');
    }
    else
    {
        if(!toTaskList) $('input[value="continueAdding"]').attr('checked', 'checked');
        $('input[value="continueAdding"]').attr('disabled', false);
        $('input[value="toStoryList"]').attr('disabled', false);
    }
}

/**
 * Load stories.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadStories(projectID)
{
    moduleID  = $('#module').val();
    setStories(moduleID, projectID);
}

/**
 * load stories of module.
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    projectID = $('#project').val();
    setStories(moduleID, projectID);
}

/* Get select of stories.*/
function setStories(moduleID, projectID)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        var storyID = $('#story').val();
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        $('#story').val(storyID);
        setPreview();
        $('#story_chosen').remove();
        $("#story").chosen();
    });
}

$(document).ready(function()
{
    setStoryRelated();

    $('#selectAllUser').on('click', function()
    {
        var $assignedTo = $('#assignedTo');
        if($assignedTo.attr('multiple')) 
        {
            $assignedTo.children('option').attr('selected', 'selected');
            $assignedTo.trigger('chosen:updated');
        }
    });

    $('[data-toggle=tooltip]').tooltip();

    $(window).resize();

    /* show team menu. */
    $('[name^=multiple]').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#assignedTo, #assignedTo_chosen').addClass('hidden');
            $('.team-group').removeClass('hidden');
            $('#estimate').attr('readonly', true);
        }
        else
        {
            $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
            $('.team-group').addClass('hidden');
            $('#estimate').attr('readonly', false);
        }
    });


    /* Init task team manage dialog */
    var $taskTeamEditor = $('#taskTeamEditor').batchActionForm(
    {
        idStart: 0,
        idEnd: 5,
        chosen: true,
        datetimepicker: false,
        colorPicker: false,
    });
    var taskTeamEditor = $taskTeamEditor.data('zui.batchActionForm');

    var adjustButtons = function()
    {
        var $deleteBtn = $taskTeamEditor.find('.btn-delete');
        if ($deleteBtn.length == 1) $deleteBtn.addClass('disabled').attr('disabled', 'disabled');
    };

    $taskTeamEditor.on('click', '.btn-add', function()
    {
        var $newRow = taskTeamEditor.createRow(null, $(this).closest('tr'));
        $newRow.addClass('highlight');
        setTimeout(function()
        {
            $newRow.removeClass('highlight');
        }, 1600);
        adjustButtons();
    }).on('click', '.btn-delete', function()
    {
        var $row = $(this).closest('tr');
        $row.addClass('highlight').fadeOut(700, function()
        {
            $row.remove();
            adjustButtons();
        });
    });

    adjustButtons();
});

$('#modalTeam .btn').click(function()
{
    var team = '';
    var time = 0;
    $('[name*=team]').each(function()
    {
        if($(this).find('option:selected').text() != '')
        {
            team += ' ' + $(this).find('option:selected').text();
        }

        estimate = parseFloat($(this).parents('td').next('td').find('[name*=teamEstimate]').val());
        if(!isNaN(estimate))
        {
            time += estimate;
        }

        $('#teamMember').val(team);
        $('#estimate').val(time);
    })
});
