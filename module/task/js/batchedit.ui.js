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
            taskMembers[teamAccount] = users[teamAccount];
        });
    }
    else
    {
        if(row.status == 'closed') members['closed'] = 'Closed';
        taskMembers = members;
    }

    if(row.assignedTo && taskMembers[row.assignedTo] == undefined) taskMembers[row.assignedTo] = users[row.assignedTo];
    let $assignedTo = $row.find('.form-batch-input[data-name="assignedTo"]').empty();
    $assignedTo.append('<option value=""></option>');
    for(let account in taskMembers)
    {
        $assignedTo.append('<option value="' + account + '">' + taskMembers[account] + '</option>');
    }
}
