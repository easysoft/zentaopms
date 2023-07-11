/**
 * Change mode event.
 *
 * @param  string $mode
 * @access public
 * @return viod
 */
window.changeMode = function(e)
{
    let mode = $(e.target).val();
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
}

$('#teamTable .team-saveBtn').on('click.team', '.btn', function()
{
    let memberCount   = '';
    let totalEstimate = 0;
    let totalConsumed = oldConsumed;
    let totalLeft     = 0;
    let error         = false;
    $('select[name^=team]').each(function()
    {
        if($(this).val() == '') return;

        memberCount++;

        let selectObj = $(this)[0];
        let realname  = selectObj.options[selectObj.selectedIndex].text;

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
    var html       = '';
    var mode       = $('#mode').val();
    var assignedTo = $('#assignedTo').val();
    if(mode != 'single')
    {
        var isTeamMember = false;
        $('select[name^=team]').each(function()
        {
            let selectObj = $(this)[0];
            let realname  = selectObj.options[selectObj.selectedIndex].text;

            if(realname == '') return;
            if($(this).val() == currentUser) isTeamMember = true;

            let account  = $(this).val();
            let selected = account == assignedTo ? 'selected' : '';

            html += "<option value='" + account + "' title='" + realname + "'" + selected + ">" + realname + "</option>";
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
    if(mode == 'linear' && $('#teamTable tr.member-doing').length == 0 && $('#teamTable tr.member-wait').length >= 1) $('[name=assignedTo]').val($('#teamTable tr.member-wait:first').find('select[name^=team]:first').val());
}
