$(function()
{
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
            if($tr.hasClass('member-wait') && (isNaN(estimate) || estimate <= 0))
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
            if(!$left.prop('readonly') && $tr.hasClass('member-wait') && (isNaN(left) || left <= 0))
            {
                  bootbox.alert(account + ' ' + leftNotEmpty);
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

        $('#left').val(totalLeft);
        updateAssignedTo();

        $('.close').click();
    });

    $('#multiple').on('click', function()
    {
        var showTeam = $(this).is(":checked");
        $('#assignedTo').val('');
        $('#left').val('');
        $('#dataPlanGroup').removeClass('required');
        $('.multi-append').empty();
        $('.teamTemplate select, .teamTemplate input').val('');
        $('.teamTemplate input[name^=teamConsumed]').val('0');
        $('.teamTemplate select').trigger('chosen:updated');

        /* Reset team modal. */
        var oldTeamCount = $('#taskTeamEditor select:disabled').length;
        var removeCount  = oldTeamCount > 6 ? 1 : (5 - oldTeamCount);
        $('#taskTeamEditor tr.teamTemplate').filter(':gt(' + removeCount + ')').remove();
        $('.member-done').find('input[name^=teamLeft]').val(0);

        /* Reset assignedTo select.*/
        $('#assignedTo_chosen').remove();
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
        $('#assignedTo').chosen();
        $('#assignedTo').trigger('chosen:updated');
    });

    $('#assignedTo').on('change', setTeamUser);
    $('#left').on('input', setTeamUser);

    $('#submit').on('click', function()
    {
        if(isMultiple)
        {
            var multiple = $('#multiple').is(":checked");
            if(!multiple)
            {
                var assignedTo = $('#assignedTo').val();
                if(!assignedTo)
                {
                    bootbox.alert(teamNotEmpty);
                    return false;
                }
            }

            var estimate = parseInt($('#left').val());
            if(isNaN(estimate) || estimate <= 0)
            {
                bootbox.alert(multiple ? teamLeftEmpty : leftNotEmpty);
                return false;
            }

            $('#assignedTo').val('');
        }
    });
});

/**
 * Set team user form.
 *
 * @access public
 * @return void
 */
function setTeamUser()
{
    $('.multi-append').empty();

    var assignedTo = $('#assignedTo').val();
    var estimate   = parseInt($('#left').val());
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
        var teamLine = '<input type="hidden" name="team[]" value="' + assignedTo + '">';
        teamLine += '<input type="hidden" name="teamSource[]" value="' + assignedTo + '">';
        teamLine += '<input type="hidden" name="teamEstimate[]" value="' + estimate + '">';
        teamLine += '<input type="hidden" name="teamConsumed[]" value="0">';
        teamLine += '<input type="hidden" name="teamLeft[]" value="' + estimate + '">';
        $('.multi-append').html(teamLine);
    }
}

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
        $('[name=assignedTo]').attr('disabled', 'disabled').trigger('chosen:updated');
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
    if(multiple && mode == 'linear' && $('#modalTeam tr.member-doing').length == 0 && $('#modalTeam tr.member-wait').length >= 1) $('[name=assignedTo]').val($('#modalTeam tr.member-wait:first').find('select[name^=team]:first').val());
    $('#assignedTo').trigger('chosen:updated');
}
