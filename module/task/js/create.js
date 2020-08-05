/**
 * Load module, stories and members.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadAll(projectID)
{
    loadModuleMenu(projectID);
    loadProjectStories(projectID);
    loadProjectMembers(projectID);
}

/**
 * Load team members of the project.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadProjectMembers(projectID)
{
    $.get(createLink('project', 'ajaxGetMembers', 'projectID=' + projectID + '&assignedTo=' + $('#assignedTo').val()), function(data)
    {
        $('#assignedTo_chosen').remove();
        $('#assignedTo').next('.picker').remove();
        $('#assignedTo').replaceWith(data);
        $('#assignedTo').attr('name', 'assignedTo[]').chosen();

        $('.modal-dialog #taskTeamEditor tr').each(function()
        {
            $(this).find('#team_chosen').remove();
            $(this).find('#team').next('.picker').remove();
            $(this).find('#team').replaceWith(data);
            $(this).find('#assignedTo').attr('id', 'team').attr('name', 'team[]').chosen();
        });
    });
}

/**
 * Load stories of the project.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    $.get(createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=0&storyID=' + $('#story').val()), function(data)
    {
        $('#story_chosen').remove();
        $('#story').next('.picker').remove();
        $('#story').replaceWith(data);
        $('#story').addClass('filled').chosen();
    });
}

/**
 * Load module of the project.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadModuleMenu(projectID)
{
    var link = createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + projectID + '&viewtype=task');
    $('#moduleIdBox').load(link, function(){$('#module').chosen();});
}

/* Copy story title as task title. */
function copyStoryTitle()
{
    var storyTitle = $('#story option:selected').text();
    var startPosition = storyTitle.indexOf(':') + 1;
    if (startPosition > 0) {
        var endPosition   = storyTitle.lastIndexOf('(');
        storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);
    }

    $('#name').attr('value', storyTitle);
    $('#estimate').val($('#storyEstimate').val());
    $('#desc').val($('#storyDesc').val());

    $('.pri-text span:first').removeClass().addClass('pri' + $('#storyPri').val()).text($('#storyPri').val());
    $('select#pri').val($('#storyPri').val());

    $('#desc').closest('td').find('.ke-container .ke-edit .kindeditor-ph').toggle($('#storyDesc').val() == '');
    window.editor.desc.html($('#storyDesc').val());
}

/* Set the assignedTos field. */
function setOwners(result)
{
    $("#multipleBox").removeAttr("checked");
    $('.team-group').addClass('hidden');
    $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
    $('#assignedTo').next('.picker').removeClass('hidden');
    if(result == 'affair')
    {
        $('#assignedTo').attr('multiple', 'multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen();
        $('.affair').hide();
        $('.team-group').addClass('hidden');
        $('#selectAllUser').removeClass('hidden');
    }
    else if($('#assignedTo').attr('multiple') == 'multiple')
    {
        $('#assignedTo').removeAttr('multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen();
        $('.affair').show();
        $('#selectAllUser').addClass('hidden');
    }
}

/* Set preview and module of story. */
function setStoryRelated()
{
    setPreview();
    setStoryModule();
}

/* Set the story module. */
function setStoryModule()
{
    var storyID = $('#story').val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            $('#module').val(storyInfo.moduleID);
            $("#module").trigger("chosen:updated");

            $('#storyEstimate').val(storyInfo.estimate);
            $('#storyPri'     ).val(storyInfo.pri);
            $('#storyDesc'    ).val(storyInfo.spec);
        });
    }
}

/* Set the story priview link. */
function setPreview()
{
    if(!$('#story').val())
    {
        $('#preview').addClass('hidden');
        $('#copyButton').addClass('hidden');
        $('div.colorpicker').css('right', '1px');//Adjust for task #4151;
        $('.title-group.required > div').removeAttr('id', 'copyStory-input').addClass('.required');
    }
    else
    {
        storyLink  = createLink('story', 'view', "storyID=" + $('#story').val());
        var concat = config.requestType != 'GET' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview').removeClass('hidden');
        $('#preview a').attr('href', storyLink);
        $('#copyButton').removeClass('hidden');
        $('.title-group.required > div').attr('id', 'copyStory-input').removeClass('.required');
        $('div.colorpicker').css('right', '57px');//Adjust for task #4151;
    }

    setAfter();
}

/**
 * Set after locate.
 *
 * @access public
 * @return void
 */
function setAfter()
{
    if($("#story").length == 0 || $("#story").select().val() == '')
    {
        if($('input[value="continueAdding"]').attr('checked') == 'checked')
        {
            $('input[value="toTaskList"]').attr('checked', 'checked');
        }
        $('input[value="continueAdding"]').attr('disabled', 'disabled');
        $('input[value="toStoryList"]').attr('disabled', 'disabled');
    }
    else
    {
        if(!toTaskList) $('input[value="continueAdding"]').attr('checked', 'checked');
        $('input[value="continueAdding"]').attr('disabled', false);
        $('input[value="toStoryList"]').attr('disabled', false);
    }
}

/**
 * Load stories.
 *
 * @param  int    $projectID
 * @access public
 * @return void
 */
function loadStories(projectID)
{
    moduleID  = $('#module').val();
    setStories(moduleID, projectID);
}

/**
 * load stories of module.
 *
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    projectID = $('#project').val();
    setStories(moduleID, projectID);
}

/* Get select of stories.*/
function setStories(moduleID, projectID)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        var storyID = $('#story').val();
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        if($('#story').length == 0 && $('#storyBox').length != 0) $('#storyBox').html(stories);

        $('#story').val(storyID);
        setPreview();
        $('#story_chosen').remove();
        $('#story').next('.picker').remove();
        $("#story").addClass('filled').chosen();
    });
}

function toggleSelectTestStory()
{
    if(!$('#selectTestStoryBox').hasClass('hidden') && $('#selectTestStory').prop('checked'))
    {
        $('#module').closest('tr').addClass('hidden');
        $('#multipleBox').closest('td').addClass('hidden');
        $('#story').closest('tr').addClass('hidden');
        $('#estStarted').closest('tr').addClass('hidden');
        $('#estimate').closest('.table-col').addClass('hidden');
        $('#testStoryBox').removeClass('hidden');
    }
    else
    {
        $('#module').closest('tr').removeClass('hidden');
        $('#multipleBox').closest('td').removeClass('hidden');
        $('#story').closest('tr').removeClass('hidden');
        $('#estStarted').closest('tr').removeClass('hidden');
        $('#estimate').closest('.table-col').removeClass('hidden');
        $('#testStoryBox').addClass('hidden');
    }
}

function addItem(obj)
{
    var $tr = $(obj).closest('tr');
    $tr.after($tr.clone());
    var $nextTr = $tr.next();
    $nextTr.find('#testAssignedTo').val($('#assignedTo').val());
    $nextTr.find('#testStory').closest('td').find('.chosen-container').remove();
    $nextTr.find('#testStory').closest('td').find('select').val('').chosen();
    $nextTr.find('#testPri').closest('td').find('.chosen-container').remove();
    $nextTr.find('#testPri').closest('td').find('select').chosen();
    $nextTr.find('#testAssignedTo').closest('td').find('.chosen-container').remove();
    $nextTr.find('#testAssignedTo').closest('td').find('select').chosen();
    $nextTr.find('.form-date').val('').datepicker();
}

function removeItem(obj)
{
    if($(obj).closest('table').find('tbody tr').size() > 1) $(obj).closest('tr').remove();
}

function markTestStory()
{
    $('#testStoryBox select[name^="testStory"]').each(function()
    {
        var $select = $(this);
        $select.find('option').each(function()
        {
            var $option = $(this);
            var value = $option.attr('value');
            var tests = testStoryIdList[value];
            $option.attr('data-data', value).toggleClass('has-test', !!(tests && tests !== '0'));
        });
        $select.trigger("chosen:updated");
    });

    var getStoriesHasTest = function()
    {
        var storiesHasTest = {};
        $('#testStoryBox table tbody>tr').each(function()
        {
            var $tr = $(this);
            storiesHasTest[$tr.find('select[name^="testStory"]').val()] = true;
        });
        console.log(storiesHasTest);
        return storiesHasTest;
    };

    $('#testStoryBox').on('chosen:showing_dropdown', 'select[name^="testStory"],.chosen-with-drop', function()
    {
        var storiesHasTest = getStoriesHasTest();
        var $container     = $(this).closest('td').find('.chosen-container');
        setTimeout(function()
        {
            $container.find('.chosen-results>li').each(function()
            {
                var $li = $(this);
                $li.toggleClass('has-new-test', !!storiesHasTest[$li.data('data')]);
            });
        }, 100);
    });
}

$(document).ready(function()
{
    $('#pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });
    $('#type').change(function()
    {
        $('#selectTestStoryBox').toggleClass('hidden', $(this).val() != 'test');
        toggleSelectTestStory();
    });

    setStoryRelated();
    markTestStory();

    $('#selectAllUser').on('click', function()
    {
        var $assignedTo = $('#assignedTo');
        if($assignedTo.attr('multiple'))
        {
            $assignedTo.children('option').attr('selected', 'selected');
            $assignedTo.trigger('chosen:updated');
        }
    });

    var preAssign = '';
    $('#assignedTo').change(function()
    {
        var assign = $(this).val();
        $('#testStoryBox').find('select[name^="testAssignedTo"]').each(function()
        {
            if($(this).val() == '' || $(this).val() == preAssign) $(this).val(assign).trigger('chosen:updated');
        });
        preAssign = assign;
    });

    $('[data-toggle=tooltip]').tooltip();

    $(window).resize();

    /* show team menu. */
    $('[name^=multiple]').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#assignedTo, #assignedTo_chosen').addClass('hidden');
            $('#assignedTo').next('.picker').addClass('hidden');
            $('.team-group').removeClass('hidden');
            $('#estimate').attr('readonly', true);
        }
        else
        {
            $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
            $('#assignedTo').next('.picker').removeClass('hidden');
            $('.team-group').addClass('hidden');
            $('#estimate').attr('readonly', false);
        }
        $('#dataPlanGroup').fixInputGroup();
    });

    /* Init task team manage dialog */
    var $taskTeamEditor = $('#taskTeamEditor').batchActionForm(
    {
        idStart: 0,
        idEnd: 5,
        chosen: true,
        datetimepicker: false,
        colorPicker: false,
    });
    var taskTeamEditor = $taskTeamEditor.data('zui.batchActionForm');

    var adjustButtons = function()
    {
        var $deleteBtn = $taskTeamEditor.find('.btn-delete');
        if ($deleteBtn.length == 1) $deleteBtn.addClass('disabled').attr('disabled', 'disabled');
    };

    $taskTeamEditor.on('click', '.btn-add', function()
    {
        var $newRow = taskTeamEditor.createRow(null, $(this).closest('tr'));
        $newRow.addClass('highlight');
        setTimeout(function()
        {
            $newRow.removeClass('highlight');
        }, 1600);
        adjustButtons();
    }).on('click', '.btn-delete', function()
    {
        var $row = $(this).closest('tr');
        $row.addClass('highlight').fadeOut(700, function()
        {
            $row.remove();
            adjustButtons();
        });
    });

    adjustButtons();

    $('#showAllModule').change(function()
    {
        var moduleID = $('#moduleIdBox #module').val();
        var extra    = $(this).prop('checked') ? 'allModule' : '';
        $('#moduleIdBox').load(createLink('tree', 'ajaxGetOptionMenu', "rootID=" + projectID + '&viewType=task&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=0&extra=' + extra), function()
        {
            $('#moduleIdBox #module').val(moduleID).attr('onchange', "setStories(this.value, " + projectID + ")").chosen();
        });
    });
});

$(document).on('click', '#testStory_chosen,#story_chosen', function()
{
    var $obj  = $(this).prev('select');
    var value = $obj.val();
    if($obj.hasClass('filled')) return false;

    $obj.empty();
    for(storyID in stories)
    {
        pinyin = (typeof(storyPinYin) == 'undefined') ? '' : storyPinYin[storyID];
        html   = "<option value='" + storyID + "' title='" + stories[storyID] + "' data-keys='" + pinyin + "'>" + stories[storyID] + "</option>";
        $obj.append(html);
    }
    $obj.val(value);
    $obj.addClass('filled');
    $obj.trigger("chosen:updated");
})

$('#modalTeam .btn').click(function()
{
    var team = '';
    var time = 0;
    $('[name*=team]').each(function()
    {
        if($(this).find('option:selected').text() != '')
        {
            team += ' ' + $(this).find('option:selected').text();
        }

        estimate = parseFloat($(this).parents('td').next('td').find('[name*=teamEstimate]').val());
        if(!isNaN(estimate))
        {
            time += estimate;
        }

        $('#teamMember').val(team);
        $('#estimate').val(time);
    })
});
