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

    const taskUsers   = [];
    let   disabled    = false;
    let   $assignedTo = $row.find('.form-batch-input[data-name="assignedTo"]').empty();
    if(teams[row.id] != undefined && ((row.assignedTo != currentUser && row.mode == 'linear') || taskMembers[currentUser] == undefined))
    {
        disabled = true;
    }
    if(row.status == 'closed') disabled = true;

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

    if(moduleGroup[executionID] != undefined)
    {
        $row.find('[data-name="module"]').find('.picker-box').on('inited', function(e, info)
        {
            let $module = info[0];
            let modules = moduleGroup[executionID];
            $module.render({items: modules});
            $module.$.setValue(row.module);
        });
    }
}

window.clickSubmit = async function(e)
{
    if(!nonStoryChildTasks) return true;
    const $taskBatchForm    = $('#taskBatchEditForm' + executionID);
    const $taskBatchFormTrs = $taskBatchForm.find('tbody tr');

    var confirmID = '';
    var tipAll    = true;
    for(let i = 0; i < $taskBatchFormTrs.length; i++)
    {
        const $currentTr = $($taskBatchFormTrs[i]);
        const taskID      = $currentTr.find('.form-batch-control[data-name=id]').find('input[name^=id]').val();
        const storyID     = $currentTr.find('.form-batch-control[data-name=story]').find('input[name^=story]').val();

        if(tasks[taskID].story == storyID) continue;
        if(!storyID && tasks[taskID].parent <= 0) continue;
        if(tasks[taskID].parent > 0)
        {
            if(storyID) confirmID = confirmID.replace('ID' + taskID + ', ', '');
            continue;
        }
        if(typeof childTasks[taskID] != 'object' || typeof nonStoryChildTasks[taskID] != 'object') continue;

        const nonStoryChildTaskIdList = Object.keys(nonStoryChildTasks[taskID]);
        if(nonStoryChildTaskIdList.length == 0) continue;

        if(tipAll) tipAll = Object.keys(childTasks[taskID]).length == nonStoryChildTaskIdList.length;

        for(let j = 0; j < nonStoryChildTaskIdList.length; j++) confirmID += 'ID' + nonStoryChildTaskIdList[j].toString() + ', ';
    }
    if(confirmID.length == 0) return true;

    if(confirmID.endsWith(', ')) confirmID = confirmID.slice(0, -2);

    let confirmTip = tipAll ? syncStoryToAllChildrenTip : syncStoryToChildrenTip;
    confirmTip     = confirmTip.replace('%s', confirmID);
    zui.Modal.confirm(confirmTip).then((res) =>
    {
        $taskBatchForm.find('[name=syncChildren]').remove();
        $taskBatchForm.append('<input type="hidden" name="syncChildren" value="' + (res ? '1' : '0') + '" />');

        const formData   = new FormData($taskBatchForm[0]);
        const confirmURL = $taskBatchForm.attr('action');
        $.ajaxSubmit({url: confirmURL, data: formData});
    });
    return false;
};

window.statusChange = function(event)
{
    const $currentTr        = $(event.target).closest('tr');
    const status            = $(event.target).val();
    const $assignedToPicker = $currentTr.find('[name^=assignedTo]').zui('picker');

    let hasClosed       = false;
    let assignedToItems = JSON.parse(JSON.stringify($assignedToPicker.options.items));
    if(status == 'closed')
    {
        for(let i = 0; i < assignedToItems.length; i++)
        {
            if(assignedToItems[i].value == 'closed') hasClosed = true;
        }
        if(!hasClosed) assignedToItems.push({key: "closed", keys: "closed c", text : "Closed", value : 'closed'});
        $assignedToPicker.render({items: assignedToItems, disabled: true});
        $assignedToPicker.$.setValue('closed');
    }
    else
    {
        for(let i = 0; i < assignedToItems.length; i++)
        {
            if(assignedToItems[i].value == 'closed')
            {
                assignedToItems.splice(i, 1);

                $assignedToPicker.render({items: assignedToItems, disabled: false});
                $assignedToPicker.$.setValue('');
            }
        }
    }
}

function checkBatchEstStartedAndDeadline(event)
{
    if(parentTasks.length == 0) return true;

    const $currentRow = $(event.target).closest('tr');
    const taskID      = $currentRow.find('[name^=id]').val();
    const parentID    = tasks[taskID].parent;
    if(typeof parentTasks[parentID] == 'undefined' || !parentTasks[parentID]) return true;

    const parentTask  = parentTasks[parentID];
    const field       = $(event.target).closest('.form-batch-control').data('name');
    const estStarted  = $currentRow.find('[name^=estStarted]').val();
    const deadline    = $currentRow.find('[name^=deadline]').val();
}
