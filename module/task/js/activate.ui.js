$(function()
{
    $('.team-group, [data-dismiss="modal"]').on('click', function()
    {
        $('#modalTeam').show();
    });

    $('#confirmButton').on('click', function()
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

            let $tr      = $(this).closest('tr');
            let account  = $(this).val();
            let realname = members[account];

            let estimate = parseFloat($tr.find('[name^=teamEstimate]').val());
            if(!isNaN(estimate)) totalEstimate += estimate;
            if($tr.hasClass('member-wait') && (isNaN(estimate) || estimate <= 0))
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
            if(!$left.prop('readonly') && $tr.hasClass('member-wait') && (isNaN(left) || left <= 0))
            {
                  zui.Modal.alert(realname + ' ' + leftNotEmpty);
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

        $('#left').val(totalLeft);
        updateAssignedTo();

        $('#modalTeam').hide();
    });

    $('input[name=multiple]').on('click', function()
    {
        let showTeam = $(this).is(":checked");
        $('#assignedTo').val('');
        $('#left').val('');
        $('#dataPlanGroup').removeClass('required');
        $('.multi-append').empty();
        $('.teamTemplate select, .teamTemplate input').val('');
        $('.teamTemplate input[name^=teamConsumed]').val('0');

        /* Reset team modal. */
        let oldTeamCount = $('#taskTeamEditor select:disabled').length;
        let removeCount  = oldTeamCount > 6 ? 1 : (5 - oldTeamCount);
        $('#taskTeamEditor tr.teamTemplate').filter(':gt(' + removeCount + ')').remove();
        $('.member-done').find('input[name^=teamLeft]').val(0);

        /* Reset assignedTo select.*/
        $('#assignedTo').replaceWith(assignedToHtml);

        if(showTeam)
        {
            $('#assignedTo').attr('disabled', true);
            $('#left').attr('readonly', true);
            $('.team-group').removeClass('hidden');
        }
        else
        {
            $('.team-group').addClass('hidden');
            $('#assignedTo').removeAttr('disabled');
            $('#left').removeAttr('readonly');
            $('#dataPlanGroup').addClass('required');
        }
    });

    $('#assignedTo').on('change', setTeamUser);
    $('#left').on('input', setTeamUser);

    $('#submit').on('click', function()
    {
        if(isMultiple)
        {
            let multiple = $('#multiple').is(":checked");
            if(!multiple)
            {
                let assignedTo = $('#assignedTo').val();
                if(!assignedTo)
                {
                    zui.Modal.alert(teamNotEmpty);
                    return false;
                }
            }

            let estimate = parseInt($('#left').val());
            if(isNaN(estimate) || estimate <= 0)
            {
                zui.Modal.alert(multiple ? teamLeftEmpty : leftNotEmpty);
                return false;
            }

            $('#assignedTo').val('');
        }
    });
});

/* Update assignedTo. */
function updateAssignedTo()
{
    let html       = '';
    let multiple   = $('#multiple').prop('checked');
    let assignedTo = $('#assignedTo').val();
    if(multiple)
    {
        let isTeamMember = false;
        let mode         = $('#mode').val();
        $('select[name^=team]').each(function()
        {
            if(!$(this).val()) return;
            if($(this).val() == currentUser) isTeamMember = true;

            let account  = $(this).val();
            let realName = members[account];
            let selected = account == assignedTo ? 'selected' : '';

            html += "<option value='" + account + "' title='" + realName + "'" + selected + ">" + realName + "</option>";
        });
        $('[name=assignedTo]').attr('disabled', 'disabled');
    }
    else
    {
        for(key in members)
        {
            let selected = key == assignedTo ? 'selected' : '';
            html += "<option value='" + key + "' title='" + members[key] + "'" + selected + ">" + members[key] + "</option>";
        }
    }

    $('#assignedTo').html(html);
    if(multiple && mode == 'linear' && $('#modalTeam tr.member-doing').length == 0 && $('#modalTeam tr.member-wait').length >= 1) $('[name=assignedTo]').val($('#modalTeam tr.member-wait:first').find('select[name^=team]:first').val());
}

/**
 * Set team user form.
 *
 * @access public
 * @return void
 */
function setTeamUser()
{
    $('.multi-append').empty();

    let assignedTo = $('#assignedTo').val();
    let estimate   = parseInt($('#left').val());
    if(!assignedTo || !estimate) return;

    if(taskMode == 'multi')
    {
        $('.member-done').each(function()
        {
            if($(this).find('select').val() == assignedTo) $(this).find('input[name^=teamLeft]').val(estimate);
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


/* Mange team. */
$('#teamTable').on('click.team', '.btn-add', function()
{
    let $newRow = $(this).closest('tr').clone();
    $newRow.find('td.required').removeClass('required');
    $newRow.find('select,input').val('');
    $(this).closest('tr').after($newRow);

    toggleBtn();
    setLineIndex();
    disableMembers();
})

$('#teamTable').on('click.team', '.btn-delete', function()
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
    let $deleteBtn = $('#teamTable').find('.btn-delete');
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
        let $teams  = $('#teamTable').find('select[name^=team]');
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
            let $this = $(this);
            let value = $this.val();
            $this.find('option:disabled').removeAttr('disabled');
            $.each(members, function(i, account)
            {
                if(account == value) return;
                $this.find('option[value=' + account + ']').attr('disabled', 'disabled');
            })
        });
        $('#teamTable').find('tr.hidden').remove();
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
    let $teams      = $('#teamTable').find('select#team');
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

    if($('#teamTable').find('td > .btn-delete:enabled').length == 1) return false;

    return true;
}

$('#teamTable').on('change', 'select[name^=team]', function()
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

$('#teamTable').find('select#team:enabled').each(function()
{
    $(this).closest('tr').find('input[name^=teamLeft]').closest('td').toggleClass('required', $(this).val() != '')
});
