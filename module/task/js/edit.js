$(function() 
{
    $("#story, #mailto").chosen();
    $('.record-estimate-toggle').modalTrigger({width:900, type:'iframe', afterHide: function(){parent.location.href=parent.location.href;}});
})

/**
 * Load module, stories and members. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadAll(projectID)
{
    if(!changeProjectConfirmed)
    {
        firstChoice = confirm(confirmChangeProject);
        changeProjectConfirmed = true;    // Only notice the user one time.
    }
    if(changeProjectConfirmed && firstChoice)
    {
        loadModuleMenu(projectID); 
        loadProjectStories(projectID);
        loadProjectMembers(projectID);
    }
    else
    {
        $('#project').val(oldProjectID);
        $("#project").trigger("chosen:updated");
    }
}

/**
 * Load module of the project. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadModuleMenu(projectID)
{
    var link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + projectID + '&viewtype=task');
    $('#moduleIdBox').load(link, function(){$('#module').chosen();});
}

/**
 * Load stories of the project. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    var link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=0&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen();});
}

/**
 * Load team members of the project. 
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectMembers(projectID)
{
    var link = createLink('project', 'ajaxGetMembers', 'projectID=' + projectID + '&assignedTo=' + oldAssignedTo);
    $('#assignedToIdBox').load(link, function(){$('#assignedToIdBox').find('select').chosen()});
}

/* empty function. */
function setPreview(){}

$(document).ready(function()
{
    /* show team menu. */
    $('[name=multiple]').change(function()
    {
        var checked = $(this).prop('checked');
        if(checked)
        {
            $('#teamTr').removeClass('hidden');
        }
        else
        {
            $('#teamTr').addClass('hidden');
        }
    });

    /* Init task team manage dialog */
    var $taskTeamEditor = $('#taskTeamEditor').batchActionForm(
    {
        idStart: 0,
        idEnd: newRowCount - 1,
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
