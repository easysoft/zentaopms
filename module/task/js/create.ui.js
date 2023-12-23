$(function()
{
    if(hideStory)
    {
        $("input[name='after'][value='toStoryList']").parent().hide();
        $("input[name='after'][value='continueAdding']").parent().hide();
        $("input[name='after'][value='toTaskList']").prop('checked', true);
    }

    if(task.mode != '')
    {
        $('[name="multiple"]').prop('checked', true);
        toggleTeam();
    }

    setStoryRelated();
})

/**
 * 根据多人任务是否勾选展示团队。
 * Show team menu box.
 *
 * @access public
 * @return void
 */
function toggleTeam()
{
    if($('[name^=multiple]').prop('checked'))
    {
        $('.add-team').removeClass('hidden');
        $('#assignedTo').addClass('hidden');
        $('.assignedToList').removeClass('hidden');
    }
    else
    {
        $('.add-team').addClass('hidden');
        $('#assignedTo').removeClass('hidden');
        $('.assignedToList').addClass('hidden');
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
function typeChange(e)
{
    const result = $(e.target).val();
    /* Change assigned person to multiple selection, and hide multiple team box. */
    if(result == 'affair')
    {
        $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
        $('#assignedTo').next('.picker').removeClass('hidden');

        $('#assignedTo').attr('multiple', 'multiple');
        $('.affair').hide();
        $('.assignedToList').addClass('hidden');
        $('.add-team').addClass('hidden');
        $("[name='multiple']").prop("checked", false);
        $('#selectAllUser').removeClass('hidden');
    }
    /* If assigned selection is multiple, remove multiple and hide the selection of select all members. */
    else if($('#assignedTo').attr('multiple') == 'multiple')
    {
        $('#assignedTo').removeAttr('multiple');
        $('.affair').show();
        $('#selectAllUser').addClass('hidden');
    }

    /* If the execution has story list, toggle between hiding and displaying the selection of select test story box. */
    if(lifetime != 'ops' && attribute != 'request' && attribute != 'review' && vision != 'lite')
    {
        $('#selectTestStoryBox').toggleClass('hidden', result != 'test');
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
function toggleSelectTestStory(executionID)
{
    if(typeof(executionID) == 'undefined') executionID = window.executionID;

    $('#testStoryBox').load($.createLink('task', 'ajaxGetTestStories', 'executionID=' + executionID + '&taskID=' + taskID));
    if(!$('#selectTestStoryBox').hasClass('hidden') && $('#selectTestStory').prop('checked'))
    {
        $('#module').closest('.form-group').addClass('hidden');
        $('#multipleBox').closest('.form-group').addClass('hidden');
        $('#story').closest('.form-row').addClass('hidden');
        $('#estStarted').closest('.form-row').addClass('hidden');
        if(!$('#estimate').hasClass('hidden')) $('#estimate').parent().prev().addClass('hidden');
        if(!$('#estimate').hasClass('hidden')) $('#estimate').parent().addClass('hidden');
        $('#testStoryBox').removeClass('hidden');

        $('[name^=multiple]').prop('checked', false);
        toggleTeam();
    }
    else
    {
        $('#module').closest('.form-group').removeClass('hidden');
        $('#multipleBox').closest('.form-group').removeClass('hidden');
        if(showFields.indexOf('story') != -1) $('#story').closest('.form-row').removeClass('hidden');
        $('#estStarted').closest('.form-row').removeClass('hidden');
        if(!$('#estimate').hasClass('hidden')) $('#estimate').parent().prev().removeClass('hidden');
        if(!$('#estimate').hasClass('hidden')) $('#estimate').parent().removeClass('hidden');
        $('#testStoryBox').addClass('hidden');
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
    const getModuleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + executionID + '&viewtype=task&branch=0&rootModuleID=0&returnType=items&fieldID=&needManage=0&extra=' + extra);
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
        if(stories)
        {
            stories = JSON.parse(stories);
            const $storyPicker = $('input[name=story]').zui('picker');
            $storyPicker.render({items: stories});
        }

        setPreview();

        /* If there is no story option, select will be hidden and text will be displayed; otherwise, the opposite is true */
        if(stories.length)
        {
            $('#story').closest('.form-group').removeClass('hidden');
            $('.empty-story-tip').closest('.form-group').addClass('hidden');
        }
        else
        {
            $('#story').closest('.form-group').addClass('hidden');
            $('.empty-story-tip').closest('.form-group').removeClass('hidden');
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
    $.get(getLaneLink, function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $lanePicker = $('input[name=lane]').zui('picker');
            $lanePicker.render({items: data.items});
        }
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
    $('#copyButton').prop('checked', false);
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
        $('.title-group.required > div').removeAttr('id', 'copyStory-input').addClass('.required');
    }
    else
    {
        let storyLink = $.createLink('execution', 'storyView', "storyID=" + storyID);
        let concat    = storyLink.indexOf('?') < 0 ? '?' : '&';

        if(storyLink.indexOf("onlybody=yes") < 0) storyLink = storyLink + concat + 'onlybody=yes';

        $('#preview').removeClass('hidden');
        $('#preview .btn').attr('data-url', storyLink);
        $('#preview .btn').attr('data-size', 'lg');
        $('.title-group.required > div').attr('id', 'copyStory-input').removeClass('.required');
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
    if($("#story").length == 0 || $("input[name=story]").val())
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
    let $tr = $(obj).closest('tr');
    $tr.after($tr.clone());
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

        assignedToList += `<div class='picker-multi-selection' data-index=${index}><span class='text'>${realname}</span><div class="picker-deselect-btn btn size-xs ghost"><span class="close"></span></div></div>`;
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
        $('#estimate').val(totalEstimate);
    }

    /* 将选中的团队成员展示在指派给后面. */
    const regex = /<i class="icon icon-arrow-right"><\/i>(?!.*<i class="icon icon-arrow-right"><\/i>)/;
    assignedToList = assignedToList.replace(regex, '');
    $('div.assignedToList').prepend(assignedToList);

    zui.Modal.hide();
    return false;
})

$('#taskCreateForm').on('click', '.assignedToList .picker-multi-selection', function()
{
    /* 团队成员必须大于1人. */
    if($(this).closest('.assignedToList').find('.picker-multi-selection').length == 2)
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
})

window.copyStoryTitle = function()
{
    let storyTitle = $('#story span.picker-single-selection').text();
    let startPosition = storyTitle.indexOf(':') + 1;
    if (startPosition > 0) {
        let endPosition   = storyTitle.lastIndexOf('(');
        storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);
    }

    $('#name').attr('value', storyTitle);
    $('#estimate').attr('value', $('input[name=storyEstimate]').val());
    $('#desc').attr('value', $('input[name=storyDesc]').val());
    $('input[name=pri]').zui('pripicker').$.setValue($('input[name=storyPri]').val());
}

window.showAllModule = function()
{
    $('input[name=isShowAllModule]').val('1');
    const getModuleLink = $.createLink('tree', 'ajaxGetOptionMenu', "rootID=" + executionID + '&viewType=task&branch=0&rootModuleID=0&returnType=items&fieldID=&needManage=0&extra=allModule');

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

window.closeModal = function(e)
{
    const $modal = $(e.target).closest('.modal');
    if($modal.length == 0) return;

    const modalID = $modal.attr('id');
    zui.Modal.hide('#' + modalID);
}
