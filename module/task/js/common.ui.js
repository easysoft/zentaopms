$('#teamTable').on('click.team', '.btn-add', function()
{
    var $newRow = $(this).closest('tr').clone();

    let options = zui.Picker.query("[name^='team']").options;

    $newRow.find('.picker-box').remove();
    $newRow.find('td').eq(1).append('<div class="picker-box" id="line"></div>');

    $newRow.find('input').val('');
    $newRow.find('[name^=teamConsumed]').val(0);
    $newRow.find('.required').removeClass('required');
    $(this).closest('tr').after($newRow);

    toggleBtn();
    let index    = setLineIndex();
    let newID    = $newRow.find('[id^=line]').attr('id');
    let taskMode = $('[name=mode]').val() ? $('[name=mode]').val() : '';

    /* Init team's select picker. */
    options.defaultValue = '';
    if(taskMode == 'multi')
    {
        let members = [];
        let $teams  = $('#teamTable').find('.picker-box [name^=team]');
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
    }

    new zui.Picker(`#${newID}`, options);

})

$('#teamTable').on('click.team', '.btn-delete', function()
{
    var $row = $(this).closest('tr');
    if(!checkRemove($row.index())) return;

    $row.remove();
    toggleBtn();
    setLineIndex();
    disableMembers();
});

/* 切换串行/并行 展示/隐藏工序图标. */
$('.form').on('change.team', '[name="mode"]', function()
{
    if($(this).val() == 'multi')
    {
        $('#teamTable td .icon-angle-down').addClass('hidden');
    }
    else
    {
        $('#teamTable td .icon-angle-down').removeClass('hidden');
    }
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
        $(this).closest('tr').find('[id^="line"]').attr('id', 'line' + index);
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
    var $deleteBtn = $('#teamTable').find('.btn-delete');
    if($deleteBtn.length == 1)
    {
        $deleteBtn.addClass('hidden');
    }
    else
    {
        $deleteBtn.removeClass('hidden');
    }
};

function onPageUnmount()
{
    $('#modalTeam').off('.saveTeam');
}

/**
 * Disable user select box.
 *
 * @access public
 * @return void
 */
function disableMembers()
{
    let mode = $('[name=mode]').val() ? $('[name=mode]').val() : '';
    if(mode == 'multi')
    {
        let members = [];
        let $teams  = $('#teamTable').find('.picker-box [name^=team]');
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

        $('#teamTable').find('tr.hidden').remove();
    }
}

$('#teamTable').on('change.team', '.picker-box [name^=team]', function()
{
    $(this).closest('tr').find('input[name^=teamLeft]').closest('td').toggleClass('required', $(this).val() != '')

    disableMembers();
})

/**
 * Check if it can be removed.
 *
 * @param  int    $removeIndex
 * @access public
 * @return void
 */
function checkRemove(removeIndex)
{
    let $teams      = $('#teamTable').find('.picker-box [name^=team]');
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

window.confirmBug = function(confirmTip, taskID, bugID)
{
    zui.Modal.confirm(confirmTip).then((res) => {
        if(res)
        {
            loadPage($.createLink('bug', 'view', `bugID=${bugID}`));
        }
        else
        {
            loadPage($.createLink('task', 'view', `taskID=${taskID}`));
        }
    });
}
