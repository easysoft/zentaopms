window.renderRowData = function($row, index, row)
{
    const executionID  = row.execution;
    let   members      = [];
    let   teamAccounts = executionTeams[executionID] != undefined ? executionTeams[executionID] : [];
    $.each(teamAccounts, function(index, teamAccount)
    {
        members[teamAccount] = users[teamAccount];
    });

    let taskMembers = [];
    if(teams[row.id] != undefined)
    {
        teamAccounts = teams[row.id];
        $.each(teamAccounts, function(index, teamAccount)
        {
            taskMembers[teamAccount.account] = users[teamAccount.account];
        });
    }
    else
    {
        if(row.status == 'closed') members['closed'] = 'Closed';
        taskMembers = members;
    }

    const taskUsers = [];
    let   disabled  = false;
    let $assignedTo = $row.find('.form-batch-input[data-name="assignedTo"]').empty();
    if(teams[row.id] != undefined && ((row.assignedTo != currentUser && row.mode == 'linear') || taskMembers[currentUser] == undefined))
    {
        disabled = true;
    }

    if(row.assignedTo && taskMembers[row.assignedTo] == undefined) taskMembers[row.assignedTo] = users[row.assignedTo];
    for(let account in taskMembers) taskUsers.push({value: account, text: taskMembers[account]});

    $row.find('[data-name="assignedTo"]').find('.picker-box').on('inited', function(e, info)
    {
        let $assignedTo = info[0];
        $assignedTo.render({items: taskUsers, disabled: disabled});
    });

    if(teams[row.id] != undefined || row.parent < 0)
    {
        $row.find('.form-batch-input[data-name="estimate"]').attr('disabled', 'disabled');
        $row.find('.form-batch-input[data-name="consumed"]').attr('disabled', 'disabled');
        $row.find('.form-batch-input[data-name="left"]').attr('disabled', 'disabled');
    }
}
