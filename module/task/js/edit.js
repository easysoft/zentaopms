$(function()
{
    $('.record-estimate-toggle').modalTrigger({width:900, type:'iframe', afterHide: function(){parent.location.href=parent.location.href;}});
    if(!newRowCount) $('#taskTeamEditor tr.member').last().addClass('member-last');

    if($('#consumedSpan').parent().find('button').hasClass('disabled'))
    {
        $('#consumedSpan').parent().find('button').attr('disabled','disabled')
    }

    $('#mode').change(function()
    {
        var mode = $(this).val();
        if(mode != 'single')
        {
            if(mode != 'multi')
            {
                $('#assignedTo').attr('disabled', 'disabled').trigger('chosen:updated')
            }
            else
            {
                $('#assignedTo').removeAttr('disabled').trigger('chosen:updated')
            }

            $('.team-group').removeClass('hidden');
            $('#estimate').attr('readonly', 'readonly');
            $('#left').attr('readonly', 'readonly');
        }
        else
        {
            $('#assignedTo').removeAttr('disabled').trigger('chosen:updated')
            $('.team-group').addClass('hidden');
            $('#estimate').removeAttr('readonly');
            $('#left').removeAttr('readonly');
        }
    })
})

/**
 * Load module, stories and members.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadAll(executionID)
{
    if(confirm(confirmChangeExecution))
    {
        loadModuleMenu(executionID);
        loadExecutionStories(executionID);
        loadExecutionMembers(executionID);
    }
    else
    {
        $('#execution').val(oldExecutionID);
        $("#execution").trigger("chosen:updated");
    }
}

/**
 * Load module of the execution.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadModuleMenu(executionID)
{
    var link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + executionID + '&viewtype=task');
    $('#moduleIdBox').load(link, function(){$('#module').chosen();});
}

/**
 * Load stories of the execution.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadExecutionStories(executionID, moduleID)
{
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    var link = createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=0&moduleID=' + moduleID + '&storyID=' + oldStoryID);
    $('#storyIdBox').load(link, function(){$('#story').chosen();});
}

/**
 * Load team members of the execution.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadExecutionMembers(executionID)
{
    var link = createLink('execution', 'ajaxGetMembers', 'executionID=' + executionID + '&assignedTo=' + oldAssignedTo);
    $('#assignedToIdBox').load(link, function(){$('#assignedToIdBox').find('select').chosen()});
}

/* empty function. */
function setPreview(){}

$(document).ready(function()
{
    $('#showAllModule').change(function()
    {
        var moduleID = $('#moduleIdBox #module').val();
        var extra    = $(this).prop('checked') ? 'allModule' : '';
        $('#moduleIdBox').load(createLink('tree', 'ajaxGetOptionMenu', "rootID=" + executionID + '&viewType=task&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=0&extra=' + extra), function()
        {
            $('#moduleIdBox #module').val(moduleID).chosen();
        });
    });
});

$('#confirmButton').click(function()
{
    var memberCount   = '';
    var totalEstimate = 0;
    var totalConsumed = oldConsumed;
    var totalLeft     = 0;
    var error         = false;
    $('select[name^=team]').each(function()
    {
        if($(this).val() == '') return;

        memberCount++;

        var $tr     = $(this).closest('tr');
        var account = $(this).find('option:selected').text();

        var estimate = parseFloat($tr.find('[name^=teamEstimate]').val());
        if(!isNaN(estimate)) totalEstimate += estimate;
        if(isNaN(estimate) || estimate <= 0)
        {
              bootbox.alert(account + ' ' + estimateNotEmpty);
              error = true;
              return false;
        }

        var consumed = parseFloat($tr.find('[name^=teamConsumed]').val());
        if(!isNaN(consumed)) totalConsumed += consumed;

        var $left = $tr.find('[name^=teamLeft]');
        var left  = parseFloat($left.val());
        if(!isNaN(left)) totalLeft += left;
        if(!$left.prop('readonly') && (isNaN(left) || left <= 0) && team.length > 0)
        {
              bootbox.alert(account + ' ' + leftNotEmpty);
              error = true;
              return false;
        }

        if(estimate == 0 || isNaN(estimate))
        {
            $(this).val('').trigger("chosen:updated");
            bootbox.alert(estimateNotEmpty);
            error = true;
            return false;
        }
    })

    if(error) return false;

    if(memberCount < 2)
    {
        bootbox.alert(teamMemberError);
        return false;
    }

    if(totalLeft == 0 && (taskStatus == 'doing' || taskStatus == 'pause'))
    {
        if(confirm(confirmRecord))
        {
            $('#status').val('done');
            $('#status').trigger('chosen:updated');
        }
        else
        {
            return false;
        }
    }

    $('#estimate').val(totalEstimate);
    $('#consumedSpan').html(totalConsumed);
    $('#left').val(totalLeft);
    updateAssignedTo();

    $('.close').click();
});

/**
 * Update assignedTo.
 *
 * @access public
 * @return void
 */
function updateAssignedTo()
{
    var html       = '';
    var mode       = $('#mode').val();
    var assignedTo = $('#assignedTo').val();
    if(mode != 'single')
    {
        var isTeamMember = false;
        $('select[name^=team]').each(function()
        {
            if($(this).find('option:selected').text() == '') return;
            if($(this).val() == currentUser) isTeamMember = true;

            var account  = $(this).find('option:selected').val();
            var realName = $(this).find('option:selected').text();
            var selected = account == assignedTo ? 'selected' : '';

            html += "<option value='" + account + "' title='" + realName + "'" + selected + ">" + realName + "</option>";
        });

        if(mode == 'multi' && isTeamMember && mode != 'linear')
        {
            $('[name=assignedTo]').removeAttr('disabled').trigger('chosen:updated');
        }
        else
        {
            $('[name=assignedTo]').attr('disabled', 'disabled').trigger('chosen:updated');
        }
    }
    else
    {
        for(key in members)
        {
            var selected = key == assignedTo ? 'selected' : '';
            html += "<option value='" + key + "' title='" + members[key] + "'" + selected + ">" + members[key] + "</option>";
        }
    }

    $('#assignedTo').html(html);
    if(mode != 'single' && mode == 'linear' && $('#modalTeam tr.member-doing').length == 0 && $('#modalTeam tr.member-wait').length >= 1) $('[name=assignedTo]').val($('#modalTeam tr.member-wait:first').find('select[name^=team]:first').val());
    $('#assignedTo').trigger('chosen:updated');
}
