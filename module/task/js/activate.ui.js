$(document).on('#left', 'input', setTeamUser);
window.waitDom('.picker-box [name=assignedTo]', function()
{
    if(taskMode == 'linear' || taskMode == 'multi')
    {
        $('#multiple').trigger('click');
        updateAssignedTo();
        computeTotalLeft();
    }
});

window.manageTeam = function()
{
    const showTeam = $('#multiple').is(":checked");

    let $assignedToPicker = $('[name=assignedTo]').zui('picker');
    $assignedToPicker.$.setValue('');
    $assignedToPicker.render({items: teamItems, disabled: showTeam});

    $('.multi-append').empty();
    $('.member-done').find('input[name^=teamLeft]').val(0);

    $('#left').val('');
    if(showTeam)
    {
        $('#left').attr('readonly', true);
        $('.team-group').removeClass('hidden');
    }
    else
    {
        $('#left').removeAttr('readonly');
        $('.team-group').addClass('hidden');
    }

    if(taskMode == 'multi') disableMembers();
}

/**
 * 设置团队成员。
 * Set team members.
 *
 * @access public
 * @return void
 */
function setTeamUser()
{
    $('.multi-append').empty();

    const assignedTo = $('[name=assignedTo]').val();
    const estimate   = parseInt($('#left').val());

    if(!assignedTo || !estimate) return;

    if(taskMode == 'multi')
    {
        $('.member-done').each(function()
        {
            if($(this).find('[name^team]').val() == assignedTo) $(this).find('input[name^=teamLeft]').val(estimate);
        });
    }
    else
    {
        let teamLine = '<input type="hidden" name="team[]" value="' + assignedTo + '">';
        teamLine += '<input type="hidden" name="teamSource[]" value="' + assignedTo + '">';
        teamLine += '<input type="hidden" name="teamEstimate[]" value="' + estimate + '">';
        teamLine += '<input type="hidden" name="teamConsumed[]" value="0">';
        teamLine += '<input type="hidden" name="teamLeft[]" value="' + estimate + '">';
        $('.multi-append').html(teamLine);
    }
}

/**
 * 检查团队成员数量。
 * Check the number of team members.
 *
 * @access public
 * @return void
 */
$(document).off('click', '#confirmButton').on('click', '#confirmButton', function()
{
    let memberCount   = '';
    let totalEstimate = 0;
    let totalConsumed = oldConsumed;
    let totalLeft     = 0;
    let error         = false;

    $('.picker-box [name^=team]').each(function()
    {
        if($(this).val() == '') return;

        memberCount++;

        let $tr      = $(this).closest('tr');
        let account  = $tr.find('.picker-single-selection').text();
        let estimate = parseFloat($tr.find('[name^=teamEstimate]').val());

        if(!isNaN(estimate)) totalEstimate += estimate;

        if($tr.hasClass('member-wait') && (isNaN(estimate) || estimate <= 0))
        {
            zui.Modal.alert(account + ' ' + estimateNotEmpty);
            error = true;
            return false;
        }

        let consumed = parseFloat($tr.find('[name^=teamConsumed]').val());
        if(!isNaN(consumed)) totalConsumed += consumed;

        let $left = $tr.find('[name^=teamLeft]');
        let left  = parseFloat($left.val());
        if(!isNaN(left)) totalLeft += left;
        if(!$left.prop('readonly') && $tr.hasClass('member-wait') && (isNaN(left) || left <= 0))
        {
            zui.Modal.alert(account + ' ' + leftNotEmpty);
            error = true;
            return false;
        }
    });

    if(error) return false;

    if(memberCount < 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }

    $('#left').val(totalLeft);

    updateAssignedTo();
    zui.Modal.hide();
});


/**
 * 更新指派人。
 * Update assignedTo.
 *
 * @access public
 * @return void
 */
function updateAssignedTo()
{
    const multiple        = $('#multiple').is(":checked");
    let assignedTo        = $('[name=assignedTo]').val();
    let $assignedToPicker = $('[name=assignedTo]').zui('picker');
    if(multiple)
    {
        let isTeamMember    = false;
        let mode            = $('#mode').val();
        let assignedToItems = new Array();
        let index           = 0;

        $('.picker-box [name^=team]').each(function()
        {
            let $tr      = $(this).closest('tr');
            let realName = $tr.find('.picker-single-selection').text();
            if(realName == '') return;

            let account = $(this).val();

            assignedToItems[index] = {'value': account, 'text': realName};
            index ++;
        });

        if(multiple && mode == 'linear' && $('#modalTeam tr.member-doing').length == 0 && $('#modalTeam tr.member-wait').length >= 1)
        {
            index --;
            assignedTo = assignedToItems.includes(index) ? assignedToItems[index].value : '';
        }

        $assignedToPicker.render({items: assignedToItems, disabled: true});
    }
    else
    {
        $assignedToPicker.render({items: memberItems});
    }
    $assignedToPicker.$.setValue(assignedTo);
}

function computeTotalLeft()
{
    let totalLeft = 0;
    $('tr.member').each(function()
    {
        let $leftBox = $(this).find('[name^=teamLeft]');
        let left     = parseFloat($leftBox.val());
        if(!isNaN(left)) totalLeft += left;
    });
    $('#left').val(totalLeft);
}

$('#teamTable').on('change', '.picker-box [name^=team]', function()
{
    $(this).closest('tr').find('input[name^=teamLeft]').closest('td').toggleClass('required', $(this).val() != '')

    disableMembers();

    let $teamSource = $(this).siblings('[name^=teamSource]');
    if($teamSource.val() == '') return;

    let $tr      = $(this).closest('tr');
    let consumed = 0;
    let estimate = $tr.attr('data-left');;
    if($(this).val() == $teamSource.val())
    {
        consumed = $tr.attr('data-consumed');
        estimate = $tr.attr('data-estimate');
    }
    $tr.find('[name^=teamConsumed]').val(consumed);
    $tr.find('[name^=teamEstimate]').val(estimate);
});

window.clickSubmit = function()
{
    if(isMultiple)
    {
        var multiple = $('#multiple').is(":checked");
        if(!multiple)
        {
            var assignedTo = $('[name=assignedTo]').val();
            if(!assignedTo)
            {
                zui.Modal.alert(teamNotEmpty);
                return false;
            }
        }

        var estimate = parseInt($('#left').val());
        if(isNaN(estimate) || estimate <= 0)
        {
            zui.Modal.alert(multiple ? teamLeftEmpty : leftNotEmpty);
            return false;
        }

        $('#assignedTo').val('');
    }
}

window.renderRowData = function($row, index, row)
{
    $row.addClass('member member-' + (row ? row.status : 'wait'));
    $row.attr('data-estimate', row ? row.teamEstimate : 0);
    $row.attr('data-consumed', row ? row.teamConsumed : 0);
    $row.attr('data-left',     row ? row.teamLeft : 0);

    if(row && row.memberDisabled)
    {
        $row.find('[data-name=team]').find('.picker-box').on('inited', function(e, info)
        {
            const $team = info[0];
            $team.render({disabled: true});
        })

        $row.find('[data-name=ACTIONS]').find('[data-type=delete]').remove();
    }
    if(row && row.hourDisabled)
    {
        $row.find('[name^=teamEstimate]').attr('readonly', 'readonly');
        $row.find('[name^=teamLeft]').attr('readonly', 'readonly');
    }

    $row.find('[name^=teamConsumed]').attr('readonly', 'readonly');
    $row.find('[data-name=id]').addClass('center').html("<span class='team-number'>" + $row.find('[data-name=id]').text() + "</span><i class='icon-angle-down " + (taskMode == 'linear' ? '' : 'hidden') + "'><i/>");
}
