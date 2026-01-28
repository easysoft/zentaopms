window.renderRowData = function($row, index, row)
{
    const executionID  = row.execution;
    let   members      = [];
    let   teamAccounts = executionTeams[executionID] != undefined ? executionTeams[executionID] : [];
    $.each(teamAccounts, function(index, teamAccount)
    {
        members[teamAccount] = users[teamAccount];
    });

    $row.attr('data-parent', row.parent);

    let taskMembers = [];
    if(row.mode != '' && teams[row.id] != undefined)
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
    $row.find('.form-batch-input[data-name="assignedTo"]').empty();
    if(teams[row.id] != undefined && ((row.mode == 'linear' && row.status != 'done') || taskMembers[currentUser] == undefined)) disabled = true;
    if(row.status == 'closed') disabled = true;

    if(row.assignedTo && taskMembers[row.assignedTo] == undefined) taskMembers[row.assignedTo] = users[row.assignedTo];
    for(let account in taskMembers) taskUsers.push({value: account, text: taskMembers[account]});

    if(parentTasks[row.parent] != undefined && taskDateLimit == 'limit')
    {
        const parentTask = parentTasks[row.parent];
        $row.find('[id^="estStarted"]').on('inited', function(e, info)
        {
            if(parentTask.estStarted == '')
            {
                row.estStarted = '';
                if(parentTasks[row.id] != undefined) parentTasks[row.id].estStarted = '';
                info[0].render({readonly: true});
            }
        });
        $row.find('[id^="deadline"]').on('inited', function(e, info)
        {
            if(parentTask.deadline == '')
            {
                row.deadline = '';
                if(parentTasks[row.id] != undefined) parentTasks[row.id].deadline = '';
                info[0].render({readonly: true});
            }
        });
    }

    $row.find('[data-name="assignedTo"]').find('.picker-box').on('inited', function(e, info)
    {
        const $assignedTo   = info[0];
        const manageLink    = noSprintPairs[row.project] != undefined ? manageLinkList['project'].replace('{projectID}', row.project) : manageLinkList['execution'].replace('{executionID}', row.execution);
        const pickerToolbar = manageLink && teams[row.id] == undefined ? [{'className': 'text-primary manageTeamBtn', 'key': 'manageTeam', 'text': manageTeamMemberText, 'icon': 'plus-solid-circle', 'url': manageLink, 'data-toggle': 'modal', 'data-size': 'lg', 'data-dismiss': 'pick'}] : '';

        if(!pickerToolbar) $row.find('.taskAssignedToBox').removeClass('taskAssignedToBox');
        if(pickerToolbar) $row.find('.taskAssignedToBox').attr('data-object', noSprintPairs[row.project] != undefined ? row.project : row.execution);
        $assignedTo.render({items: taskUsers, disabled: disabled, toolbar: pickerToolbar});
    });

    if(row.status == 'wait') $row.find('[data-name="status"]').find('.picker-box').on('inited', function(e, info) { info[0].render({items: noPauseStatusList}); });

    if(teams[row.id] != undefined || row.isParent > 0)
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
        const $storyPicker = info[0];
        if(stories.length > 0)
        {
            const storyItems = stories[row.module] != undefined ? stories[row.module] : [];
            $storyPicker.render({items: storyItems});
            $storyPicker.$.setValue(row.story);
        }
        else
        {
            const getStoryLink = $.createLink('task', 'ajaxGetStories', 'executionID=' + row.execution + '&moduleID=' + row.module);
            $.getJSON(getStoryLink, function(executionStories)
            {
                $storyPicker.render({items: executionStories});
                $storyPicker.$.setValue(row.story);
            });
        }
    });
}

window.clickSubmit = async function(e)
{
    if(!nonStoryChildTasks) return true;
    const $taskBatchForm    = $('#taskBatchEditForm' + executionID);
    const $taskBatchFormTrs = $taskBatchForm.find('tbody tr');

    var confirmID = '';
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


        for(let j = 0; j < nonStoryChildTaskIdList.length; j++) confirmID += 'ID' + nonStoryChildTaskIdList[j].toString() + ', ';
    }
    if(confirmID.length == 0) return true;

    if(confirmID.endsWith(', ')) confirmID = confirmID.slice(0, -2);

    let confirmTip = syncStoryToChildrenTip.replace('%s', confirmID);
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
    if(taskDateLimit != 'limit') return;

    const $currentRow = $(event.target).closest('tr');
    const taskID      = $currentRow.find('[name^=id]').val();
    const parentID    = tasks[taskID].parent;

    const field       = $(event.target).closest('.form-batch-control').data('name');
    const estStarted  = $currentRow.find('[name^=estStarted]').val();
    const deadline    = $currentRow.find('[name^=deadline]').val();
    const parentTask  = parentTasks[parentID] ? parentTasks[parentID] : {estStarted: '', deadline: ''};

    if(field == 'estStarted')
    {
        const $estStartedTd = $currentRow.find('td[data-name=estStarted]');
        $estStartedTd.find('.date-tip').remove();

        const $childrenEstStarted = $(event.target).closest('tbody').find('tr[data-parent="' + taskID + '"]').find('[name^=estStarted]');

        $childrenEstStarted.each(function()
        {
            let $childDatePicker = $(this).zui('datePicker');
            $childDatePicker.render({readonly: estStarted.length == 0});
            if(estStarted.length == 0) $childDatePicker.$.setValue('');

            checkBatchEstStartedAndDeadline({target: this});
        });

        if(estStarted.length > 0)
        {
            let $datetip = $('<div class="date-tip"></div>');
            let parentEstStarted = typeof tasks[parentID] == 'undefined' || $(event.target).closest('tbody').find('[name="estStarted[' + parentID + ']"]').length == 0 ? parentTask.estStarted : $(event.target).closest('tbody').find('[name="estStarted[' + parentID + ']"]').val();
            if(parentEstStarted.length > 0 && estStarted < parentEstStarted) $datetip.append('<div class="form-tip text-danger">' + overParentEstStartedLang.replace('%s', parentEstStarted) + '</div>');

            let childEstStarted = childrenDateLimit[taskID] ? childrenDateLimit[taskID].estStarted : '';
            $childrenEstStarted.each(function()
            {
                if(childEstStarted.length == 0 || ($(this).val().length > 0 && $(this).val() < childEstStarted)) childEstStarted = $(this).val();
            });
            if(childEstStarted.length > 0 && estStarted > childEstStarted)
            {
                $datetip.append('<div class="form-tip text-warning">' + overChildEstStartedLang.replace('%s', childEstStarted) + '<span class="ignore-date ignore-child underline">' + ignoreLang + '</span></div>');
                $datetip.off('click', '.ignore-child').on('click', '.ignore-child', function(e){ignoreTip(e)});
            }
            $estStartedTd.append($datetip);
        }
    }

    if(field == 'deadline')
    {
        const $deadlineTd = $currentRow.find('td[data-name=deadline]');
        $deadlineTd.find('.date-tip').remove();

        const $childrenDeadline = $(event.target).closest('tbody').find('tr[data-parent="' + taskID + '"]').find('[name^=deadline]');
        $childrenDeadline.each(function()
        {
            let $childDatePicker = $(this).zui('datePicker');
            $childDatePicker.render({readonly: deadline.length == 0});
            if(deadline.length == 0) $childDatePicker.$.setValue('');

            checkBatchEstStartedAndDeadline({target: this});
        });

        if(deadline.length > 0)
        {
            let $datetip = $('<div class="date-tip"></div>');

            let parentDeadline = typeof tasks[parentID] == 'undefined' || $(event.target).closest('tbody').find('[name="deadline[' + parentID + ']"]').length == 0 ? parentTask.deadline : $(event.target).closest('tbody').find('[name="deadline[' + parentID + ']"]').val();
            if(parentDeadline.length > 0 && deadline > parentDeadline) $datetip.append('<div class="form-tip text-danger">' + overParentDeadlineLang.replace('%s', parentDeadline) + '</div>');

            let childDeadline = childrenDateLimit[taskID] ? childrenDateLimit[taskID].deadline : '';
            $childrenDeadline.each(function()
            {
                if(childDeadline.length == 0 || ($(this).val().length > 0 && $(this).val() > childDeadline)) childDeadline = $(this).val();
            });
            if(childDeadline.length > 0 && deadline < childDeadline)
            {
                $datetip.append('<div class="form-tip text-warning">' + overChildDeadlineLang.replace('%s', childDeadline) + '<span class="ignore-date ignore-child underline">' + ignoreLang + '</span></div>');
                $datetip.off('click', '.ignore-child').on('click', '.ignore-child', function(e){ignoreTip(e)});
            }
            $deadlineTd.append($datetip);
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

window.computeForParent = function(e)
{
    const $this    = $(e.target);
    const field    = $this.attr('data-name');
    const parentID = $this.closest('tr').attr('data-parent');
    if(parentID == '0') return;

    const $parent = $('input[name="id[' + parentID + ']"]');
    if($parent.length == 0) return;

    const oldParentHour = parseFloat($parent.closest('tr').find('input[data-name="' + field + '"]').val());

    let parentHour = 0;
    $('tr[data-parent="' + parentID + '"]').each(function()
    {
        parentHour += parseFloat($(this).find('input[data-name="' + field + '"]').val());
    });
    if(oldParentHour < parentHour) $parent.closest('tr').find('input[data-name="' + field + '"]').val(parentHour.toFixed(2));
}