$(function()
{
    $('.record-estimate-toggle').modalTrigger({width:900, type:'iframe', afterHide: function(){parent.location.href=parent.location.href;}});

    $('#modalTeam').on('change', 'select#team', function()
    {
        $(this).closest('tr').find('input[id^=teamEstimate]').closest('.input-group').toggleClass('required', $(this).val() != '')
    })
    $('#modalTeam select:enabled').change()
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
    /* show team menu. */
    $('[name=multiple]').change(function()
    {
        var checked = $(this).prop('checked');
        if(checked)
        {
            $('#teamTr').removeClass('hidden');
            $('.modeBox').removeClass('hidden');
            $('#mode').removeAttr('disabled').trigger('chosen:updated');
            $('#parent').val('');
            $('#parent').trigger('chosen:updated');
            $('#parent').closest('tr').addClass('hidden');
            $('#estimate').attr('disabled', 'disabled');
            $('#left').attr('disabled', 'disabled');

            var mode = $('#mode').val();
            if((mode == 'linear' && currentUser != oldAssignedTo) || !team[currentUser]) $('[name=assignedTo]').attr('disabled', 'disabled').trigger('chosen:updated');
        }
        else
        {
            $('#teamTr').addClass('hidden');
            $('.modeBox').addClass('hidden');
            $('#mode').attr('disabled', 'disabled').trigger('chosen:updated');
            $('#parent').closest('tr').removeClass('hidden');
            $('#estimate').removeAttr('disabled');
            $('#left').removeAttr('disabled');
            $('[name=assignedTo]').removeAttr('disabled').trigger('chosen:updated');
        }

        updateAssignedTo();
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
    /* Unique team. */
    var values = [];
    $('select[name^=team]').each(function(i)
    {
        value = $(this).val();
        if(value == '') return;
        if($.inArray(value, values) >= 0)
        {
            $(this).closest('tr').addClass('hidden');
            return;
        }
        values.push(value);
    });

    $('select[name^=team]').closest('tr.hidden').remove();

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

        estimate = parseFloat($tr.find('[name^=teamEstimate]').val());
        if(!isNaN(estimate)) totalEstimate += estimate;
        if(isNaN(estimate) || estimate == 0)
        {
              bootbox.alert(account + ' ' + estimateNotEmpty);
              error = true;
              return false;
        }

        consumed = parseFloat($tr.find('[name^=teamConsumed]').val());
        if(!isNaN(consumed)) totalConsumed += consumed;

        left = parseFloat($tr.find('[name^=teamLeft]').val());
        if(!isNaN(left)) totalLeft += left;
        if(!$tr.hasClass('member-done') && (isNaN(left) || left == 0))
        {
              bootbox.alert(account + ' ' + estimateNotEmpty);
              error = true;
              return false;
        }

        var requiredFieldList = ',' + requiredFields + ',';
        if(requiredFieldList.indexOf(',estimate,') >= 0 && (estimate == 0 || isNaN(estimate)))
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
        bootbox.alert(totalLeftError);
        return false;
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
    var multiple   = $('#multiple').prop('checked');
    var assignedTo = $('#assignedTo').val();
    if(multiple)
    {
        var isTeamMember = false;
        var mode         = $('#mode').val();
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
    if(multiple && mode == 'linear' && $('#modalTeam tr.member-doing').length == 0 && $('#modalTeam tr.member-wait').length >= 1) $('[name=assignedTo]').val($$('#modalTeam tr.member-wait:first').find('select[name^=team]:first').val());
    $('#assignedTo').trigger('chosen:updated');
}
