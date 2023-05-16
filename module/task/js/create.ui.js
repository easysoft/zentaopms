$(function()
{
    if(hideStory)
    {
        $("input[name='after'][value='toStoryList']").parent().hide();
        $("input[name='after'][value='continueAdding']").parent().hide();
        $("input[name='after'][value='toTaskList']").prop('checked', true);
    }
})

/**
 * 根据多人任务是否勾选展示团队。
 * Show team menu box.
 *
 * @access public
 * @return void
 */
function showTeamBox()
{
    if($('[name^=multiple]').prop('checked'))
    {
        $('.team-group').removeClass('hidden');
        $('.modeBox').removeClass('hidden');
        $('#estimate').attr('readonly', true);
    }
    else
    {
        $('.team-group').addClass('hidden');
        $('.modeBox').addClass('hidden');
        $('#estimate').removeAttr('readonly');
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
        $("#multipleBox").removeAttr("checked");
        $('.team-group').addClass('hidden');
        $('.modeBox').addClass('hidden');
        $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
        $('#assignedTo').next('.picker').removeClass('hidden');

        $('#assignedTo').attr('multiple', 'multiple');
        $('.affair').hide();
        $('.team-group').addClass('hidden');
        $('.modeBox').addClass('hidden');
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
    if(lifetime != 'ops' && attribute != 'request' && attribute != 'review')
    {
        $('#selectTestStoryBox').toggleClass('hidden', result != 'test');
        toggleSelectTestStory();
    }
}

/**
 * 根据选择研发需求是否勾选切换相关字段的展示与隐藏。
 * Dynamically control whether task fields are hidden based on selection status of selectTestStory.
 *
 * @access public
 * @return void
 */
function toggleSelectTestStory()
{
    if(!$('#selectTestStoryBox').hasClass('hidden') && $('#selectTestStory').prop('checked'))
    {
        $('#module').closest('.form-group').addClass('hidden');
        $('#multipleBox').closest('.form-group').addClass('hidden');
        $('#story').closest('.form-row').addClass('hidden');
        $('#estStarted').closest('.form-row').addClass('hidden');
        $('#estimate').parent().prev().addClass('hidden');
        $('#estimate').parent().addClass('hidden');
        $('#testStoryBox').removeClass('hidden');

        $('[name^=multiple]').prop('checked', false);
        showTeamBox();
    }
    else
    {
        $('#module').closest('.form-group').removeClass('hidden');
        $('#multipleBox').closest('.form-group').removeClass('hidden');
        if(showFields.indexOf('story') != -1) $('#story').closest('.form-row').removeClass('hidden');
        $('#estStarted').closest('.form-row').removeClass('hidden');
        $('#estimate').parent().prev().removeClass('hidden');
        $('#estimate').parent().removeClass('hidden');
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
    const executionID = $(this).val();
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
    const extra         = $('#showAllModule').prop('checked') ? 'allModule' : '';
    const getModuleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + executionID + '&viewtype=task&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=0&extra=' + extra);
    $.get(getModuleLink, function(data)
    {
        $('#module').replaceWith(data);
    });
}

/**
 * 加载执行的需求。
 * Load stories of the execution
 *
 * @access public
 * @return void
 */
function loadExecutionStories()
{
    var   storyID      = $('#story').val();
    const executionID  = $('#execution').val();
    const moduleID     = $('#module').val();
    const getStoryLink = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=0&moduleID=' + moduleID + '&storyID=' + storyID + '&number=&type=full&status=active');
    $.get(getStoryLink, function(stories)
    {
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        $('#story').removeAttr('onchange');
        if($('#story').length == 0 && $('#storyBox').length != 0) $('#storyBox').html(stories);

        $('#story').val(storyID);
        setPreview();
        $('#story').next('.picker').remove();

        /* If there is no story option, select will be hidden and text will be displayed; otherwise, the opposite is true */
        if($('#story option').length > 1 || parseInt(hasProduct) == 0)
        {
            $('#story').closest('.form-group').removeClass('hidden');
            $('.empty-story-tip').closest('.form-group').addClass('hidden');

            $('#taskCreateForm').on('change', '#story', setStoryRelated);
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
        $('#assignedTo').replaceWith(data);
        $('#assignedTo').attr('name', 'assignedTo[]');
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
    const regionID    = $(this).val();
    const getLaneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=task&field=lane');
    $.get(getLaneLink, function(data)
    {
        $('#lane').replaceWith(data);
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
    if(!Number($('#story').val()))
    {
        $('#preview').addClass('hidden');
        $('.title-group.required > div').removeAttr('id', 'copyStory-input').addClass('.required');
    }
    else
    {
        var storyLink = $.createLink('execution', 'storyView', "storyID=" + $('#story').val());
        var concat    = storyLink.indexOf('?') < 0 ? '?' : '&';

        if(storyLink.indexOf("onlybody=yes") < 0) storyLink = storyLink + concat + 'onlybody=yes';

        $('#preview').removeClass('hidden');
        $('#preview .btn').attr('data-url', storyLink);
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
    var storyID = $('#story').val();
    if(storyID)
    {
        var link = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            if(storyInfo)
            {
                $('#module').val(storyInfo.moduleID);
                $("#module").trigger("chosen:updated");

                $('#storyEstimate').val(storyInfo.estimate);
                $('#storyPri').val(storyInfo.pri);
                $('#storyDesc').val(storyInfo.spec);
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
    if($("#story").length == 0 || $("#story").val() == '')
    {
        /* If the exeuction doesn't have stories, hide the selections of story. */
        if($('input[value="continueAdding"]').attr('checked') == 'checked')
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
        $('input[value="continueAdding"]').attr('disabled', false);
        $('input[value="toStoryList"]').attr('disabled', false);
    }
}
