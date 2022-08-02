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
});

$('#confirmButton').click(function()
{
    /* Unique team. */
    $('select[name^=team]').each(function(i)
    {
        value = $(this).val();
        if(value == '') return;
        $('select[name^=team]').each(function(j)
        {
            if(i <= j) return;
            if(value == $(this).val()) $(this).closest('tr').addClass('hidden');
        })
    });

    $('select[name^=team]').closest('tr.hidden').remove();

    var memberCount   = '';
    var totalEstimate = 0;
    var totalConsumed = oldConsumed;
    var totalLeft     = 0;
    $('select[name^=team]').each(function()
    {
        if($(this).find('option:selected').text() == '') return;

        memberCount++;

        estimate = parseFloat($(this).parents('td').next('td').find('[name^=teamEstimate]').val());
        if(!isNaN(estimate)) totalEstimate += estimate;

        consumed = parseFloat($(this).parents('td').next('td').find('[name^=teamConsumed]').val());
        if(!isNaN(consumed)) totalConsumed += consumed;

        left = parseFloat($(this).parents('td').next('td').find('[name^=teamLeft]').val());
        if(!isNaN(left)) totalLeft += left;
    })
    $('#estimate').val(totalEstimate);
    $('#consumedSpan').html(totalConsumed);
    $('#left').val(totalLeft);

    if(memberCount < 2)
    {
        alert(teamMemberError);
        return false;
    }
    if(totalLeft == 0 && (taskStatus == 'doing' || taskStatus == 'pause'))
    {
        alert(totalLeftError);
        return false;
    }
    $('.close').click();
});
