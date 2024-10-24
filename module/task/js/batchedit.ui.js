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

    $row.find('[data-name="story"]').find('.picker-box').on('inited', function(e, info)
    {
        const storyItems   = stories[row.module] != undefined ? stories[row.module] : [];
        const $storyPicker = info[0];
        $storyPicker.render({items: storyItems});
        $storyPicker.$.setValue(row.story);
    });
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

    if(field == 'estStarted')
    {
        let parentEstStarted = typeof tasks[parentID] == 'undefined' || $(event.target).closest('tbody').find('[name="estStarted[' + parentID + ']"]').length == 0 ? parentTask.estStarted : $(event.target).closest('tbody').find('[name="estStarted[' + parentID + ']"]').val();
        if(estStarted.length > 0 && estStarted < parentEstStarted)
        {
            const $estStartedTd = $currentRow.find('td[data-name=estStarted]');
            if($estStartedTd.find('.date-tip').length == 0 || $estStartedTd.find('.date-tip .form-tip').length > 0)
            {
                $estStartedTd.find('.date-tip').remove();

                let $datetip = $('<div class="date-tip"></div>');
                $datetip.append('<div class="form-tip text-warning">' + overParentEstStartedLang.replace('%s', parentEstStarted) + '<span class="ignore-date underline">' + ignoreLang + '</div>');
                $datetip.off('click', '.ignore-date').on('click', '.ignore-date', function(e){ignoreTip(e)});
                $estStartedTd.append($datetip);
            }
        }
    }

    if(field == 'deadline')
    {
        let parentDeadline = typeof tasks[parentID] == 'undefined' || $(event.target).closest('tbody').find('[name="deadline[' + parentID + ']"]').length == 0 ? parentTask.deadline : $(event.target).closest('tbody').find('[name="deadline[' + parentID + ']"]').val();
        if(deadline.length > 0 && deadline > parentDeadline)
        {
            const $deadlineTd = $currentRow.find('td[data-name=deadline]');
            if($deadlineTd.find('.date-tip').length == 0 || $deadlineTd.find('.date-tip .form-tip').length > 0)
            {
                $deadlineTd.find('.date-tip').remove();

                let $datetip = $('<div class="date-tip"></div>');
                $datetip.append('<div class="form-tip text-warning">' + overParentDeadlineLang.replace('%s', parentDeadline) + '<span class="ignore-date underline">' + ignoreLang + '</div>');
                $datetip.off('click', '.ignore-date').on('click', '.ignore-date', function(e){ignoreTip(e)});
                $deadlineTd.append($datetip);
            }
        }
    }
}

/**
 * Get select of stories.
 *
 * @access public
 * @return void
 */
function setStories(event)
{
    const $module      = $(event.target);
    const $currentRow  = $module.closest('tr');
    const moduleID     = $module.val();
    const getStoryLink = $.createLink('task', 'ajaxGetStories', 'executionID=' + executionID + '&moduleID=' + moduleID);

    let $row = $currentRow;
    while($row.length)
    {
        const $storyPicker = $row.find('[name^=story]').zui('picker');
        const storyID      = $row.find('[name^=story]').val();
        $.getJSON(getStoryLink, function(stories)
        {
            $storyPicker.render({items: stories})
            $storyPicker.$.setValue(storyID);
        });

        $row = $row.next('tr');
        if(!$row.find('td[data-name="module"][data-ditto="on"]').length) break;
    }
}

/**
 * Set preview.
 *
 * @access public
 * @return void
 */
function setStoryRelated(event)
{
    let $story      = $(event.target).closest('td').find('input[name^=story]');
    let $currentRow = $(event.target).closest('tr');
    let storyID     = $story.val();
    let link        = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID + '&pageType=batch');
    let $row        = $currentRow;

    while($row.length)
    {
        const $module = $row.find('input[name^="module"]');

        if(storyID > 0)
        {
            $.getJSON(link, function(data)
            {
                const storyInfo = data['storyInfo'];
                $module.zui('picker').$.setValue(parseInt(storyInfo.moduleID), true);
            });
        }

        $row = $row.next('tr');
        if(!$row.find('td[data-name="story"][data-ditto="on"]').length) break;
    }
}
