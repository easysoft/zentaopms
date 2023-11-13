$(document).on('#left', 'input', setTeamUser);

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
function checkTeam()
{
    let memberCount   = '';
    let totalEstimate = 0;
    let totalConsumed = oldConsumed;
    let totalLeft     = 0;
    let error         = false;

    $('.team-select [name^=team]').each(function()
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
}


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

        $('.team-select [name^=team]').each(function()
        {
            let $tr      = $(this).closest('tr');
            let realName = $tr.find('.picker-single-selection').text();
            if(realName == '') return;

            let account = $(this).val();

            assignedToItems[index] = {'value': account, 'text': realName};
            index ++;
        });

        if(multiple && mode == 'linear' && $('#modalTeam tr.member-doing').length == 0 && $('#modalTeam tr.member-wait').length >= 1) assignedTo = assignedToItems[0].value;

        $assignedToPicker.render({items: assignedToItems, disabled: true});
    }
    else
    {
        $assignedToPicker.render({items: memberItems});
    }
    $assignedToPicker.$.setValue(assignedTo);
}

$('#teamForm').on('click.team', '.btn-add', function()
{
    /* Copy row and set value is empty.  */
    let $newRow = $(this).closest('tr').clone();
    $newRow.find('td.required').removeClass('required');
    $newRow.find('input').val('');

    /* Get the maximum index for the team. */
    let index   = 0;
    let options = zui.Picker.query("[name^='team']").options;
    $(".team-select").each(function()
    {
        let id = $(this).attr('id').substring(4);

        id = parseInt(id);
        id ++;

        index = id > index ? id : index;
    });

    /* Reset the team dropdown container. */
    $newRow.find('.team-select.picker-box').remove();
    $newRow.find('td').eq(1).append('<div class="team-select picker-box" id="team' + index + '"></div>');

    /* Add a new row. */
    $(this).closest('tr').after($newRow);

    /* Init team's select picker. */
    options.defaultValue = '';
    options.disabled     = false;

    let members = [];
    let $teams  = $('#teamForm').find('.team-select [name^=team]');
    for(i = 0; i < $teams.length; i++)
    {
        let value = $teams.eq(i).val();
        if(members.includes(value))
        {
            $teams.eq(i).closest('tr').addClass('hidden');
            continue;
        }
        if(value != '') members.push(value);
    }

    $.each(options.items, function(i, item)
    {
        if(item.value == '') return;
        options.items[i].disabled = members.includes(item.value);
    });
    new zui.Picker(`#team${index}`, options);

    /* Process releate data. */
    toggleBtn();
    setLineIndex();
});


$('#teamForm').on('click.team', '.btn-delete', function()
{
    let $row = $(this).closest('tr');
    if(isMultiple && !checkRemove($row.index())) return;

    $row.remove();
    toggleBtn();
    disableMembers();
    setLineIndex();
});


/**
 * Set line number.
 *
 * @access public
 * @return void
 */
function setLineIndex()
{
    let index = 1;
    $('.team-number').each(function()
    {
        $(this).text(index);
        index ++;
    });
}

/**
 * Check delete button hide or not.
 *
 * @access public
 * @return void
 */
function toggleBtn()
{
    let $deleteBtn = $('#teamForm').find('.btn-delete');
    if($deleteBtn.length == 1)
    {
        $deleteBtn.addClass('hidden');
    }
    else
    {
        $deleteBtn.removeClass('hidden');
    }
};

/**
 * Disable user select box.
 *
 * @access public
 * @return void
 */
function disableMembers()
{
    let mode = $('#mode').val();
    if(mode == 'multi')
    {
        let members = [];
        let $teams  = $('#teamForm').find('.team-select [name^=team]');
        for(i = 0; i < $teams.length; i++)
        {
            let value = $teams.eq(i).val();
            if(members.includes(value))
            {
                $teams.eq(i).closest('tr').addClass('hidden');
                continue;
            }
            if(value != '') members.push(value);
        }

        $teams.each(function()
        {
            let $team       = $(this);
            let account     = $team.val();
            let $teamPicker = $team.zui('picker');
            let teamItems   = $teamPicker.options.items;
            $.each(teamItems, function(i, item)
            {
                if(item.value == '') return;
                teamItems[i].disabled = members.includes(item.value) && item.value != account;
            })

            $teamPicker.render({items: teamItems});
        });

        $('#teamForm').find('tr.hidden').remove();
    }
}


/**
 * Check if it can be removed.
 *
 * @param  int    $removeIndex
 * @access public
 * @return void
 */
function checkRemove(removeIndex)
{
    let $teams      = $('#teamForm').find('.team-select [name^=team]');
    let totalLeft   = 0;
    let memberCount = 0;
    for(i = 0; i < $teams.length; i++)
    {
        let $this = $teams.eq(i);
        let value = $this.val();
        if(value == '') continue;

        let $tr = $this.closest('tr');
        if($tr.index() == removeIndex) continue;

        memberCount++;

        let $teamLeft = $tr.find('[name^=teamLeft]');
        if($teamLeft.length > 0)
        {
            left = parseFloat($teamLeft.val());
            if(!isNaN(left)) totalLeft += left;
        }
    }

    if(memberCount < 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }

    if($('#teamForm').find('td > .btn-delete:enabled').length == 1) return false;

    return true;
}


$('#teamForm').on('change', '.team-select [name^=team]', function()
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
