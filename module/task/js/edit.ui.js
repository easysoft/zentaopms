
let showConfirmExecution = true;

/**
 * Load module, stories and members.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
window.loadAll = function()
{
    if(showConfirmExecution)
    {
        const executionID = $('[name=execution]').val();
        zui.Modal.confirm(confirmChangeExecution).then((res) => {
            if(res)
            {
                loadModuleMenu(executionID);
                loadExecutionStories(executionID);
                loadExecutionMembers(executionID);
            }
            else
            {
                $('[name=execution]').zui('picker').$.setValue(oldExecutionID);
                showConfirmExecution = false;
            }
        });
    }
    showConfirmExecution = true;
}

/**
 * Load module of the execution.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadModuleMenu(executionID)
{
    const oldModuleID = $('[name=module]').val();
    const extra       = $('#showAllModule').is(':checked') ? 'allModule' : '';
    const link        = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + executionID + '&viewtype=task&branch=0&rootModuleID=0&returnType=items&fieldID=&extra=' + extra);
    $.getJSON(link, function(moduleItems)
    {
        let $modulePicker = $('[name=module]').zui('picker');
        $modulePicker.render({items: moduleItems});
        $modulePicker.$.setValue(oldModuleID);
    });
}

/**
 * Load stories of the execution.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadExecutionStories(executionID, moduleID)
{
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    const link = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=0&moduleID=' + moduleID);
    $.getJSON(link, function(storyItems)
    {
        let $storyPicker = $('[name=story]').zui('picker');
        $storyPicker.render({items: storyItems});
        $storyPicker.$.setValue(oldStoryID);
    });
}

/**
 * Load team members of the execution.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadExecutionMembers(executionID)
{
    const link = $.createLink('execution', 'ajaxGetMembers', 'executionID=' + executionID);
    $.getJSON(link, function(assignedToItems)
    {
        let $assignedToPicker = $('[name=assignedTo]').zui('picker');
        $assignedToPicker.render({items: assignedToItems});
        $assignedToPicker.$.setValue(oldAssignedTo);
    });
}

window.loadAllModule = function()
{
    const executionID = $('[name=execution]').val();
    const oldModuleID = $('[name=module]').val();
    const extra       = $('#showAllModule').is(':checked') ? 'allModule' : '';
    const link        = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + executionID + '&viewtype=task&branch=0&rootModuleID=0&returnType=items&fieldID=&extra=' + extra);
    $.getJSON(link, function(moduleItems)
    {
        let $modulePicker = $('[name=module]').zui('picker');
        $modulePicker.render({items: moduleItems});
        $modulePicker.$.setValue(oldModuleID);
    });
}

/**
 * Change mode event.
 *
 * @param  string $mode
 * @access public
 * @return void
 */
window.changeMode = function()
{
    const mode = $('[name=mode]').val();
    if(mode != 'single')
    {
        if(mode != 'multi')
        {
            $('#assignedTo').picker({disabled: true});
        }
        else
        {
            $('#assignedTo').picker({disabled: false});
        }

        $('.team-group').removeClass('hidden');
        $('#estimate').attr('readonly', 'readonly');
        $('#left').attr('readonly', 'readonly');
        $('[name=parent]').zui('picker').$.setValue('');
        $('[name=parent]').zui('picker').render({disabled: true});
    }
    else
    {
        $('[name=parent]').zui('picker').render({disabled: false});
        $('#assignedTo').picker({disabled: false});
        $('.team-group').addClass('hidden');
        $('#estimate').removeAttr('readonly');
        $('#left').removeAttr('readonly');
    }
}


window.saveTeam = function()
{
    let team          = [];
    let totalEstimate = 0;
    let totalConsumed = 0;
    let totalLeft     = 0;
    let error         = false;
    $('#teamTable').find('.picker-box').each(function()
    {
        if(!$(this).find('[name^=team]').val()) return;

        let realname = $(this).find('.picker-single-selection').text();
        let account  = $(this).find('[name^=team]').val();

        if(!team.includes(account)) team.push(account);

        let $tr = $(this).closest('tr');

        let estimate = parseFloat($tr.find('[name^=teamEstimate]').val());
        if(!isNaN(estimate)) totalEstimate += estimate;
        if(isNaN(estimate) || estimate <= 0)
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
        if($left.length > 0 && !$left.prop('readonly') && (isNaN(left) || left <= 0) && team.length > 0)
        {
              zui.Modal.alert(realname + ' ' + leftNotEmpty);
              error = true;
              return false;
        }

        if(estimate == 0 || isNaN(estimate))
        {
            $(this).val('');
            zui.Modal.alert(estimateNotEmpty);
            error = true;
            return false;
        }
    })

    if(error) return false;

    if(team.length < 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }

    if(totalLeft == 0 && (taskStatus == 'doing' || taskStatus == 'pause'))
    {
        if(confirm(confirmRecord))
        {
            const statusPicker = $('input[name="status"]').closest('.picker').zui('picker');
            statusPicker.$.setValue('done');
        }
        else
        {
            return false;
        }
    }

    $('#estimate').val(totalEstimate);
    $('#consumedSpan').html(totalConsumed);
    $('#left').val(totalLeft);
    updateAssignedTo();

    zui.Modal.hide();
    return false;
}

/**
 * Update assignedTo.
 *
 * @access public
 * @return void
 */
function updateAssignedTo()
{
    var users      = [];
    var mode       = $('[name="mode"]').val();
    var assignedTo = $('[name="assignedTo"]').val();
    if(mode != 'single')
    {
        var isTeamMember = false;
        $('#teamTable').find('.picker-box').each(function()
        {
            let realname = $(this).find('.picker-single-selection').text();

            if(realname == '') return;

            let account = $(this).find('[name^=team]').val();
            if(account == currentUser) isTeamMember = true;

            users.push({'text': realname, 'value': account});
        });
    }
    else
    {
        for(key in members)
        {
            users.push({'text': members[key], 'value': key});
        }
    }

    /* 串行多人任务取第一个人作为指派人. */
    if(mode == 'linear' && $('#teamTable tr.member-doing').length == 0 && $('#teamTable tr.member-wait').length >= 1) assignedTo = $('#teamTable tr.member-wait').first().find('[name^=team]').first().val();

    let $assignedToPicker = $('#assignedTo').zui('picker');
    $assignedToPicker.render({items: users});
    $assignedToPicker.$.setValue(assignedTo);

    if(mode == 'multi' && isTeamMember)
    {
        $('#assignedTo').picker({disabled: false});
    }
    else
    {
        $('#assignedTo').picker({disabled: true});
    }
}

window.renderRowData = function($row, index, row)
{
    $row.addClass('member member-' + (row ? row.memberStatus : 'wait'));
    $row.attr('data-estimate', row ? row.teamEstimate : 0);
    $row.attr('data-consumed', row ? row.teamConsumed : 0);
    $row.attr('data-left',     row ? row.teamLeft : 0);

    if(['pause', 'cancel', 'closed'].includes(taskStatus))
    {
        $row.find('[data-type=add]').addClass('hidden'); // 已暂停、已取消、已关闭的任务不允许添加成员
        $row.find('[data-type=delete]').addClass('hidden'); // 已暂停、已取消、已关闭的任务不允许删除成员
        $row.find('[data-name=team]').find('.picker-box').on('inited', function(e, info)
        {
            info[0].render({disabled: true});
        })
        $row.find('#teamEstimate').attr('readonly', 'readonly');
        $row.find('#teamLeft').attr('readonly', 'readonly');
    }

    /* 复制上一行的人员下拉。*/
    $row.find('[data-name=team]').find('.picker-box').on('inited', function(e, info)
    {
        const $team = info[0];
        const $preTeam = $row.prev().find('input[name^=team]').zui('picker');
        if($preTeam != undefined) $team.render({items: $preTeam.options.items});
    })

    if(row && row.memberDisabled)
    {
        $row.find('[data-name=team]').find('.picker-box').on('inited', function(e, info)
        {
            const $team = info[0];
            $team.render({disabled: true});
        })

        $row.find('[data-type=delete]').addClass('hidden'); // 已完成的成员不允许删除
    }
    if(row && row.hourDisabled)
    {
        $row.find('[name^=teamEstimate]').attr('readonly', 'readonly');
        $row.find('[name^=teamLeft]').attr('readonly', 'readonly');
    }

    $row.find('[name^=teamConsumed]').attr('readonly', 'readonly');

    const mode = $('[name=mode]').val();
    $row.find('[data-name=id]').addClass('center').html("<span class='team-number'>" + $row.find('[data-name=id]').text() + "</span><i class='icon-angle-down " + (mode == 'linear' ? '' : 'hidden') + "'><i/>");

    setLineIndex();
}

window.loadStories = function()
{
    const executionID = $('[name=execution]').val();
    const storyID     = $('[name=story]').val();
    const moduleID    = $('[name=module]').val();
    const link        = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=' + storyID + '&pageType=&type=full&status=active');
    $.getJSON(link, function(storyItems)
    {
        let $storyPicker = $('[name=story]').zui('picker');
        $storyPicker.render({items: storyItems});
        $storyPicker.$.setValue(storyID);
    });
}

window.setStoryModule = function()
{
    var storyID = $('input[name=story]').val();
    if(storyID)
    {
        var link = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            if(storyInfo) $('input[name=module]').zui('picker').$.setValue(storyInfo.moduleID);
        });
    }
}

window.clickSubmit = async function(e)
{
    if(confirmSyncTip.length == 0 || $('[name=story]').length == 0 || $('[name=story]').val() == '' || $('[name=story]').val() == '0' || $('[name=story]').val() == taskStory) return true;

    await zui.Modal.confirm(confirmSyncTip).then((res) =>
    {
        const $taskForm = $('[formid=taskEditForm' + taskID + ']');

        $taskForm.find('[name=syncChildren]').remove();
        $taskForm.append('<input type="hidden" name="syncChildren" value="' + (res ? '1' : '0') + '" />');
    });
    return true;
};

window.statusChange = function(target)
{
    const status            = $(target).val();
    const $assignedToPicker = $('[name=assignedTo]').zui('picker');

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

window.loadStories = function()
{
    const executionID = $('[name=execution]').val();
    const storyID     = $('[name=story]').val();
    const moduleID    = $('[name=module]').val();
    const link        = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=' + storyID + '&pageType=&type=full&status=active');
    $.getJSON(link, function(storyItems)
    {
        let $storyPicker = $('[name=story]').zui('picker');
        $storyPicker.render({items: storyItems});
        $storyPicker.$.setValue(storyID);
    });
}

window.setStoryModule = function()
{
    var storyID = $('input[name=story]').val();
    if(storyID)
    {
        var link = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            if(storyInfo) $('input[name=module]').zui('picker').$.setValue(storyInfo.moduleID);
        });
    }
}

getParentEstStartedAndDeadline = function()
{
    const $parent = $('[name=parent]');
    const parent  = $parent.val();
    if(!parent)
    {
        if(taskDateLimit != 'limit') return;

        const $form = $parent.closest('form');
        $form.find('[name=estStarted]').zui('datePicker').render({disabled: false});
        $form.find('[name=deadline]').zui('datePicker').render({disabled: false});
        return;
    }

    const link = $.createLink('task', 'ajaxGetTaskEstStartedAndDeadline', 'taskID=' + parent);
    $.getJSON(link, function(data)
    {
        parentEstStarted         = data.estStarted;
        parentDeadline           = data.deadline;
        overParentEstStartedLang = data.overParentEstStartedLang;
        overParentDeadlineLang   = data.overParentDeadlineLang;

        window.checkEstStartedAndDeadline({target: $('[name=estStarted]')});
        window.checkEstStartedAndDeadline({target: $('[name=deadline]')});
    });
}

function checkEstStartedAndDeadline(event)
{
    if(taskDateLimit != 'limit') return;

    const $form       = $(event.target).closest('form');
    const field       = $(event.target).attr('name')
    const $estStarted = $form.find('[name=estStarted]');
    const estStarted  = $estStarted.val();
    const $deadline   = $form.find('[name=deadline]');
    const deadline    = $deadline.val();
    const hasParent   = $('[name=parent]').val() != '';

    const $estStartedDiv = $estStarted.closest('.form-group');
    if(field == 'estStarted' && estStarted.length > 0)
    {
        $estStartedDiv.find('#estStartedTip').remove();
        let $datetip = $('<div class="form-tip text-danger" id="estStartedTip"></div>');
        if(hasParent && parentEstStarted.length > 0 && estStarted < parentEstStarted) $datetip.append('<div>' + overParentEstStartedLang + '</div>');

        if(childDateLimit['estStarted'].length > 0 && estStarted > childDateLimit['estStarted'])
        {
            $datetip.append('<div class="form-tip text-warning">' + overChildEstStartedLang + '<span class="ignore-date ignore-child underline">' + ignoreLang + '</span></div>');
            $datetip.off('click', '.ignore-child').on('click', '.ignore-child', function (e) { ignoreTip(e) });
        }
        $estStartedDiv.append($datetip);
    }

    const $deadlineDiv = $deadline.closest('.form-group');
    if(field == 'deadline' && deadline.length > 0)
    {
        $deadlineDiv.find('#deadlineTip').remove();
        let $datetip = $('<div class="form-tip text-danger" id="deadlineTip"></div>');
        if(hasParent && parentDeadline.length > 0 && deadline > parentDeadline) $datetip.append('<div>' + overParentDeadlineLang + '</div>');

        if(childDateLimit['deadline'].length > 0 && deadline < childDateLimit['deadline'])
        {
            $datetip.append('<div class="form-tip text-warning">' + overChildDeadlineLang + '<span class="ignore-date ignore-child underline">' + ignoreLang + '</span></div>');
            $datetip.off('click', '.ignore-child').on('click', '.ignore-child', function (e) { ignoreTip(e) });
        }
        $deadlineDiv.append($datetip);
    }

    if(hasParent)
    {
        let $estStartedPicker = $estStarted.zui('datePicker');
        let $deadlinePicker   = $deadline.zui('datePicker');
        $estStartedPicker.render({disabled: parentEstStarted == ''});
        $deadlinePicker.render({disabled: parentDeadline == ''});
        if(parentEstStarted == '') $estStartedPicker.$.setValue('');
        if(parentDeadline == '') $deadlinePicker.$.setValue('');
    }
}
