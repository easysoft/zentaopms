/**
 * Change mode event.
 *
 * @param  string $mode
 * @access public
 * @return void
 */
window.changeMode = function(e)
{
    let mode = $(e.target).val();
    if(mode != 'single')
    {
        if(mode != 'multi')
        {
            $('#assignedTo').picker({disabled: true});
        }
        else
        {
            $('#assignedTo').picker({disabled: false});
        }

        $('.team-group').removeClass('hidden');
        $('#estimate').attr('readonly', 'readonly');
        $('#left').attr('readonly', 'readonly');
    }
    else
    {
        $('#assignedTo').picker({disabled: false});
        $('.team-group').addClass('hidden');
        $('#estimate').removeAttr('readonly');
        $('#left').removeAttr('readonly');
    }
}

$('#teamTable .team-saveBtn').on('click.team', '.btn', function()
{
    let memberCount   = '';
    let totalEstimate = 0;
    let totalConsumed = oldConsumed;
    let totalLeft     = 0;
    let error         = false;
    $(this).closest('#teamTable').find('.picker-box').each(function()
    {
        if(!$(this).find('[name^=team]').val()) return;

        memberCount++;

        let realname = $(this).find('.picker-single-selection').text();

        let $tr = $(this).closest('tr');

        let estimate = parseFloat($tr.find('[name^=teamEstimate]').val());
        if(!isNaN(estimate)) totalEstimate += estimate;
        if(isNaN(estimate) || estimate <= 0)
        {
            zui.Modal.alert(realname + ' ' + estimateNotEmpty);
            error = true;
            return false;
        }

        let consumed = parseFloat($tr.find('[name^=teamConsumed]').val());
        if(!isNaN(consumed)) totalConsumed += consumed;

        let $left = $tr.find('[name^=teamLeft]');
        let left  = parseFloat($left.val());
        if(!isNaN(left)) totalLeft += left;
        if(!$left.prop('readonly') && (isNaN(left) || left <= 0) && team.length > 0)
        {
              zui.Modal.alert(realname + ' ' + leftNotEmpty);
              error = true;
              return false;
        }

        if(estimate == 0 || isNaN(estimate))
        {
            $(this).val('');
            zui.Modal.alert(estimateNotEmpty);
            error = true;
            return false;
        }
    })

    if(error) return false;

    if(memberCount < 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }

    if(totalLeft == 0 && (taskStatus == 'doing' || taskStatus == 'pause'))
    {
        zui.Modal.alert(totalLeftError);
        return false;
    }

    $('#estimate').val(totalEstimate);
    $('#consumedSpan').html(totalConsumed);
    $('#left').val(totalLeft);
    updateAssignedTo();

    zui.Modal.hide();
    return false;
});

/**
 * Update assignedTo.
 *
 * @access public
 * @return void
 */
function updateAssignedTo()
{
    var users      = [];
    var mode       = $('[name="mode"]').val();
    var assignedTo = $('[name="assignedTo"]').val();
    if(mode != 'single')
    {
        var isTeamMember = false;
        $('#teamTable').find('.picker-box').each(function()
        {
            let realname = $(this).find('.picker-single-selection').text();

            if(realname == '') return;

            let account = $(this).find('[name^=team]').val();
            if(account == currentUser) isTeamMember = true;

            users.push({'text': realname, 'value': account});
        });
    }
    else
    {
        for(key in members)
        {
            users.push({'text': members[key], 'value': key});
        }
    }

    /* 串行多人任务取第一个人作为指派人. */
    if(mode == 'linear' && $('#teamTable tr.member-doing').length == 0 && $('#teamTable tr.member-wait').length >= 1) assignedTo = $('#teamTable tr.member-wait').first().find('[name^=team]').first().val();

    let $assignedToPicker = $('#assignedTo').zui('picker');
    $assignedToPicker.render({items: users});
    $assignedToPicker.$.changeState({value: assignedTo});

    if(mode == 'multi' && isTeamMember)
    {
        $('#assignedTo').picker({disabled: false});
    }
    else
    {
        $('#assignedTo').picker({disabled: true});
    }
}
