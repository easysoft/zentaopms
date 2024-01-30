window.waitDom('#form-task-create [name=type]', function(){ typeChange();})
window.waitDom('#form-task-create [name=story]', function(){setPreview();})
window.waitDom('#form-task-create [name=story]', function(){setStoryRelated();})

/**
 * 根据多人任务是否勾选展示团队。
 * Show team menu box.
 *
 * @access public
 * @return void
 */
function toggleTeam()
{
    $assignedToBox = $('.assignedToBox');
    if($('[name^=multiple]').prop('checked'))
    {
        $assignedToBox.find('.add-team').removeClass('hidden');
        $assignedToBox.find('.picker-box').addClass('hidden');
        $assignedToBox.find('.assignedToList').removeClass('hidden');
        $('input[name=estimate]').attr('disabled', true);
    }
    else
    {
        $assignedToBox.find('.add-team').addClass('hidden');
        $assignedToBox.find('.picker-box').removeClass('hidden');
        $assignedToBox.find('.assignedToList').addClass('hidden');
        $('input[name=estimate]').removeAttr('disabled');
    }
}

/**
 * 根据任务类型设置任务相关字段。
 * Set task-related fields based on the task type.
 *
 * @param  object e
 * @access public
 * @return void
 */
function typeChange()
{
    const result = $('#form-task-create [name=type]').val();

    /* Change assigned person to multiple selection, and hide multiple team box. */
    const $assignedToPicker = $('#form-task-create [name^=assignedTo]').zui('picker');
    if(result == 'affair')
    {
        $('.assignedToBox .assignedToList').addClass('hidden');
        $('.assignedToBox .add-team').addClass('hidden');
        $('[name=multiple]').prop("checked", false);
        $assignedToPicker.render({multiple: true, checkbox: true, toolbar: true});

    }
    /* If assigned selection is multiple, remove multiple and hide the selection of select all members. */
    else if($assignedToPicker.options.multiple)
    {
        $assignedToPicker.render({multiple: false, checkbox: false, toolbar: false});
        $assignedToPicker.$.setValue('');
    }

    $('#form-task-create [name=multiple]').closest('.checkbox-primary').toggleClass('hidden', result == 'affair');

    /* If the execution has story list, toggle between hiding and displaying the selection of select test story box. */
    if(lifetime != 'ops' && attribute != 'request' && attribute != 'review' && vision != 'lite')
    {
        $('#form-task-create [name=selectTestStory]').prop('checked', false);
        $('#form-task-create [name=selectTestStory]').closest('.checkbox-primary').toggleClass('hidden', result != 'test');
        toggleSelectTestStory();
    }
}

/**
 * 根据选择研发需求是否勾选切换相关字段的展示与隐藏。
 * Dynamically control whether task fields are hidden based on selection status of selectTestStory.
 *
 * @param  int    executionID
 * @access public
 * @return void
 */
function toggleSelectTestStory()
{
    if(!$('#form-task-create [name=selectTestStory]').hasClass('hidden') && $('#form-task-create [name=selectTestStory]').prop('checked'))
    {
        $('#form-task-create [data-name=module]').addClass('hidden');
        $('#form-task-create [data-name=storyBox]').addClass('hidden');
        $('#form-task-create [data-name=datePlan]').addClass('hidden');
        $('#form-task-create [data-name=estimate]').addClass('hidden');
        $('#form-task-create [name=multiple]').closest('.checkbox-primary').addClass('hidden');
        $('#form-task-create [data-name=testStoryBox]').removeClass('hidden');
        $('#testStoryBox').load($.createLink('task', 'ajaxGetTestStories', 'executionID=' + executionID + '&taskID=' + taskID));

        if($('[data-name=execution]').hasClass('hidden'))
        {
            $('#form-task-create [data-name=name]').removeClass('lite:w-full');
            $('#form-task-create [data-name=pri]').removeClass('w-1/4').addClass('w-1/2 full:w-1/4');
            $('#form-task-create [data-name=assignedToBox]').addClass('full:w-1/4');
        }
        else
        {
            $('#form-task-create [data-name=assignedToBox]').removeClass('w-1/2').addClass('w-1/4');
        }

        $('#form-task-create [name^=multiple]').prop('checked', false);
        toggleTeam();
    }
    else
    {
        $('#form-task-create [data-name=module]').removeClass('hidden');
        $('#form-task-create [data-name=storyBox]').removeClass('hidden');
        $('#form-task-create [data-name=datePlan]').removeClass('hidden');
        $('#form-task-create [data-name=estimate]').removeClass('hidden');
        $('#form-task-create [name=multiple]').closest('.checkbox-primary').removeClass('hidden');
        $('#form-task-create [data-name=testStoryBox]').addClass('hidden');

        if($('#form-task-create [data-name=execution]').hasClass('hidden'))
        {
            $('#form-task-create [data-name=name]').addClass('lite:w-full');
            $('#form-task-create [data-name=pri]').addClass('w-1/4').removeClass('w-1/2 full:w-1/4');
            $('#form-task-create [data-name=assignedBox]').removeClass('full:w-1/4');
        }
        else
        {
            $('#form-task-create [data-name=assignedToBox]').removeClass('w-1/4').addClass('w-1/2');
        }
    }
}

/**
 * 根据执行ID加载模块、需求和团队成员。
 * Load module, stories and members base on the execution id.
 *
 * @access public
 * @return void
 */
function loadAll()
{
    const executionID = $('input[name=execution]').val();
    const fieldList   = showFields + ',';
    lifetime          = lifetimeList[executionID];
    attribute         = attributeList[executionID];
    /* If the execution doesn't have story list, hide the related fields.*/
    if(lifetime == 'ops' || attribute == 'request' || attribute == 'review')
    {
        $('.storyBox').addClass('hidden');

        $("input[name='after'][value='toStoryList']").parent().hide();
        $("input[name='after'][value='continueAdding']").parent().hide();
        $("input[name='after'][value='toTaskList']").prop('checked', true);
    }
    /* If story field is showed, show the story field. */
    else if(fieldList.indexOf('story') >= 0)
    {
        $('.storyBox').removeClass('hidden');
        if($('#selectTestStory').prop('checked')) $('#selectTestStory').removeClass('hidden');

        $("input[name='after'][value='toStoryList']").parent().show();
        $("input[name='after'][value='continueAdding']").parent().show();
    }

    /* Load modules, stories and members of the execution. */
    loadModules(executionID);
    loadExecutionStories();
    loadExecutionMembers(executionID);

    $('#selectTestStory').prop('checked', false);
    toggleSelectTestStory(executionID);
}

/**
 * 加载执行的模块。
 * Load modules of the execution.
 *
 * @param  int    $executionID
 * @access public
 * @return void
 */
function loadModules(executionID)
{
    const extra         = $('input[name=isShowAllModule]') ? 'allModule' : '';
    const getModuleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + executionID + '&viewtype=task&branch=0&rootModuleID=0&returnType=items&fieldID=&extra=' + extra);
    $.get(getModuleLink, function(modules)
    {
        if(modules)
        {
            modules = JSON.parse(modules);
            const $modulePicker = $('input[name=module]').zui('picker');
            $modulePicker.render({items: modules});
        }
    });
}

/**
 * 加载执行的需求。
 * Load stories of the execution
 *
 * @access public
 * @return void
 */
window.loadExecutionStories = function()
{
    const storyID      = $('input[name="story"]').val();
    const executionID  = $('input[name="execution"]').length == 0 ? window.executionID : $('input[name="execution"]').val();
    const moduleID     = $('input[name="module"]').val();
    const getStoryLink = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=0&moduleID=' + moduleID + '&storyID=' + storyID + '&number=&type=full&status=active');
    $.get(getStoryLink, function(stories)
    {
        stories = JSON.parse(stories);
        const $storyPicker = $('input[name=story]').zui('picker');
        $storyPicker.render({items: stories});
        $storyPicker.$.setValue(storyID);

        setPreview();

        /* If there is no story option, select will be hidden and text will be displayed; otherwise, the opposite is true */
        if(stories.length)
        {
            $('.setStoryBox').removeClass('hidden');
            $('.empty-story-tip').addClass('hidden');
        }
        else
        {
            $('.setStoryBox').addClass('hidden');
            $('.empty-story-tip').removeClass('hidden');
        }

    });
}

/**
 * 加载执行的团队成员。
 * Load team members of the execution.
 *
 * @param executionID $executionID
 * @access public
 * @return void
 */
function loadExecutionMembers(executionID)
{
    $('#multipleBox').removeAttr('checked');
    $('.team-group,.modeBox').addClass('hidden');

    const getAssignedToLink = $.createLink('execution', 'ajaxGetMembers', 'executionID=' + executionID + '&assignedTo=' + $('#assignedTo').val());
    $.get(getAssignedToLink, function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $assignedToPicker = $('input[name=assignedTo]').zui('picker');
            $assignedToPicker.render({items: data});
        }
    });
}

/**
 * 加载区域对应泳道。
 * Load lanes of the region.
 *
 * @access public
 * @return void
 */
function loadLanes()
{
    const regionID    = $('input[name=region]').val();
    const getLaneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=task&field=lane');
    $.getJSON(getLaneLink, function(data)
    {
        const laneID      = data.items.length > 0 ? data.items[0].value : '';
        const $lanePicker = $('input[name=lane]').zui('picker');
        $lanePicker.$.setValue(laneID);
        $lanePicker.render({items: data.items});
    });
}

/**
 * 根据选择需求设置查看链接和所属模块。
 * Set preview and module of story.
 *
 * @access public
 * @return void
 */
function setStoryRelated()
{
    $('[name=copyButton]').prop('checked', false);
    setPreview();
    setStoryModule();
}

/**
 * 设置需求的查看链接。
 * Set the story priview link.
 *
 * @access public
 * @return void
 */
function setPreview()
{
    let storyID = $("input[name='story']").val() ? $("input[name='story']").val() : 0;
    if(parseFloat(storyID) == 0)
    {
        $('#preview').addClass('hidden');
    }
    else
    {
        let storyLink = $.createLink('execution', 'storyView', "storyID=" + storyID);
        let concat    = storyLink.indexOf('?') < 0 ? '?' : '&';

        if(storyLink.indexOf("onlybody=yes") < 0) storyLink = storyLink + concat + 'onlybody=yes';

        $('#preview').removeClass('hidden');
        $('#preview').attr('data-url', storyLink);
        $('#preview').attr('data-size', 'lg');
    }

    setAfter();
}

/**
 * 根据需求设置任务的所属模块。
 * Set the story module.
 *
 * @access public
 * @return void
 */
function setStoryModule()
{
    var storyID = $('input[name=story]').val();
    if(storyID)
    {
        var link = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            if(storyInfo)
            {
                $('input[name=module]').zui('picker').$.setValue(storyInfo.moduleID);

                $('input[name=storyEstimate]').val(storyInfo.estimate);
                $('input[name=storyPri]').val(storyInfo.pri);
                $('input[name=storyDesc]').val(storyInfo.spec);
            }
        });
    }
}

/**
 * 设置保存任务之后的选项。
 * Set after locate.
 *
 * @access public
 * @return void
 */
function setAfter()
{
    if($("input[name=story]").length == 0 || !$("input[name=story]").val() || $("input[name=story]").val() == 0)
    {
        /* If the exeuction doesn't have stories, hide the selections of story. */
        if($('input[value="continueAdding"]').prop('checked'))
        {
            $('input[value="toTaskList"]').prop('checked', true);
        }
        $('input[value="continueAdding"]').attr('disabled', 'disabled');
        $('input[value="toStoryList"]').attr('disabled', 'disabled');
    }
    else
    {
        /* If the exeuction has stories, show the selections of story. */
        if(!toTaskList) $('input[value="continueAdding"]').prop('checked', true);
        $('input[value="continueAdding"]').removeAttr('disabled');
        $('input[value="toStoryList"]').removeAttr('disabled');
    }
}

/**
 * Add a row.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.addItem = function(obj)
{
    let $newLine = $(obj).closest('tr').clone();

    /* 将已有需求下拉的最大name属性的值加1赋值给新行. */
    let index = 0;
    $("#testTaskTable [name^='testStory']").each(function()
    {
        let id = $(this).attr('name').replace(/[^\d]/g, '');

        id = parseInt(id);
        id ++;

        index = id > index ? id : index;
    });

    /* 重新初始化新一行的下拉控件. */
    $newLine.find('.c-testStory .form-group-wrapper').attr('id', `testStory${index}`).removeAttr('data-zui-picker').empty();
    $newLine.find('.c-testPri .form-group-wrapper').attr('id', `testPri${index}`).removeAttr('data-zui-picker').empty();
    $newLine.find('.c-testEstStarted .form-group-wrapper').attr('id', `testEstStarted${index}`).removeAttr('data-zui-picker').empty();
    $newLine.find('.c-testDeadline .form-group-wrapper').attr('id', `testDeadline${index}`).removeAttr('data-zui-picker').empty();
    $newLine.find('.c-testAssignedTo .form-group-wrapper').attr('id', `testAssignedTo${index}`).removeAttr('data-zui-picker').empty();
    $newLine.find('.c-estimate input').attr('id', `testEstimate${index}`).attr('name', `testEstimate[${index}]`).val('');

    $(obj).closest('tr').after($newLine);

    let storyOptions  = $(obj).closest('tr').find("[name^='testStory']").zui('picker').options;
    storyOptions.name = `testStory[${index}]`;
    new zui.Picker(`#testStory${index}`, storyOptions);

    let priOptions  = $(obj).closest('tr').find("[name^='testPri']").zui('priPicker').options;
    priOptions.name = `testPri[${index}]`;
    priOptions.defaultValue = '3';
    new zui.PriPicker(`#testPri${index}`, priOptions);

    let estStartedOptions  = $(obj).closest('tr').find("[name^='testEstStarted']").zui('datePicker').options;
    estStartedOptions.name = `testEstStarted[${index}]`;
    estStartedOptions.defaultValue = '';
    new zui.DatePicker(`#testEstStarted${index}`, estStartedOptions);

    let deadlineOptions  = $(obj).closest('tr').find("[name^='testDeadline']").zui('datePicker').options;
    deadlineOptions.name = `testDeadline[${index}]`;
    deadlineOptions.defaultValue = '';
    new zui.DatePicker(`#testDeadline${index}`, deadlineOptions);

    let assignedToOptions  = $(obj).closest('tr').find("[name^='testAssignedTo']").zui('picker').options;
    assignedToOptions.name = `testAssignedTo[${index}]`;
    assignedToOptions.defaultValue = '';
    new zui.Picker(`#testAssignedTo${index}`, assignedToOptions);
}

/**
 * Remove a row.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
window.removeItem = function(obj)
{
    if($('#testStoryBox').find('tbody tr').length == 1) return false;
    $(obj).closest('tr').remove();
}

$('#teamTable .team-saveBtn').on('click.team', '.btn', function()
{
    $('div.assignedToList').html('');

    let team            = [];
    let totalEstimate   = 0;
    let error           = false;
    let mode            = $('[name="mode"]').val();
    let assignedToList  = '';

    $(this).closest('#teamTable').find('.picker-box').each(function(index)
    {
        if(!$(this).find('[name^=team]').val()) return;

        let realname = $(this).find('.picker-single-selection').text();
        let account  = $(this).find('[name^=team]').val();
        if(!team.includes(realname)) team.push(realname);

        let estimate = parseFloat($(this).closest('tr').find('[name^=teamEstimate]').val());
        if(!isNaN(estimate) && estimate > 0) totalEstimate += estimate;

        if(realname != '' && (isNaN(estimate) || estimate <= 0))
        {
            zui.Modal.alert(realname + ' ' + estimateNotEmpty);
            error = true;
            return false;
        }

        assignedToList += `<div class='picker-multi-selection' data-index=${index} data-account=${account}><span class='text'>${realname}</span><div class="picker-deselect-btn btn size-xs ghost"><span class="close"></span></div></div>`;
        if(mode == 'linear') assignedToList += '<i class="icon icon-arrow-right"></i>';
    })

    if(error) return false;

    if(team.length < 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }
    else
    {
        $('[data-name=estimate] input').val(totalEstimate);
    }

    /* 将选中的团队成员展示在指派给后面. */
    const regex = /<i class="icon icon-arrow-right"><\/i>(?!.*<i class="icon icon-arrow-right"><\/i>)/;
    assignedToList = assignedToList.replace(regex, '');
    $('div.assignedToList').prepend(assignedToList);

    zui.Modal.hide();
    return false;
})

window.removeTeamMember = function()
{
    /* 团队成员必须大于1人. */
    if($(this).closest('.assignedToList').find('.picker-multi-selection').length == 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }

    /* 去重后查看人数. */
    let accounts = [];
    $(this).closest('.assignedToList').find('.picker-multi-selection').not(this).each(function()
    {
        const account = $(this).data('account');
        accounts.push(account);
    })

    let uniqueAccounts = [...new Set(accounts)];
    if(uniqueAccounts.length == 1)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }

    /* 删除人员前后的箭头. */
    if($(this).next('.icon').length)
    {
        $(this).next('.icon').remove();
    }
    else if($(this).prev('.icon').length)
    {
        $(this).prev('.icon').remove();
    }

    $(this).remove();

    /* 删除团队中，已经选中的人. */
    let index = $(this).data('index');
    $('#teamTable').find('tr').eq(index).remove();

    let totalEstimate = 0;

    $('#teamTable').find('[name^=teamEstimate]').each(function(index)
    {
        let estimate = parseFloat($(this).val());
        if(!isNaN(estimate) && estimate > 0) totalEstimate += estimate;
    })

    $('#estimate').val(totalEstimate);

    setLineIndex();
}

window.copyStoryTitle = function(e)
{
    if(!$('[name=story]').val() || $('[name=story]').val() == 0) return;

    let storyTitle = $('[data-name=storyBox] .setStoryBox span.picker-single-selection').text();
    let startPosition = storyTitle.indexOf(':') + 1;
    if (startPosition > 0) {
        let endPosition   = storyTitle.lastIndexOf('(');
        storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);
    }

    if($(e.target).prop('checked'))
    {
        $('[name=name]').val(storyTitle);
        $('[name=estimate]').val($('input[name=storyEstimate]').val());
        $('[name=desc]').val($('input[name=storyDesc]').val());
        $('input[name=pri]').zui('pripicker').$.setValue($('input[name=storyPri]').val());
    }
    else
    {
        $('[name=name]').val($('[name=taskName]').val());
        $('[name=estimate]').val($('[name=taskEstimate]').val());
        $('input[name=pri]').zui('pripicker').$.setValue(3);
    }
}

window.showAllModule = function(e)
{
    const extra         = $(e.target).prop('checked') ? 'allModule' : '';
    const getModuleLink = $.createLink('tree', 'ajaxGetOptionMenu', "rootID=" + executionID + '&viewType=task&branch=0&rootModuleID=0&returnType=items&fieldID=&extra=' + extra);
    $.getJSON(getModuleLink, function(modules)
    {
        const moduleID      = $('input[name=module]').val();
        const $modulePicker = $('input[name=module]').zui('picker');
        $modulePicker.render({items: modules});
        $modulePicker.$.setValue(moduleID);
    });
}

window.closeModal = function(e)
{
    const $modal = $(e.target).closest('.modal');
    if($modal.length == 0) return;

    const modalID = $modal.attr('id');
    zui.Modal.hide('#' + modalID);
}

window.saveTaskName = function(e)
{
    $('[name=taskName]').val($(e.target).val());
}

window.saveTaskEstimate = function(e)
{
    $('[name=taskEstimate]').val($(e.target).val());
}

window.changeTeamMember = function(e)
{
    $(e.target).closest('td').next().toggleClass('required', $(e.target).val() != '');
}
