/* Remove 'ditto' in first row. */
$(function()
{
    removeDitto();
    if($('#batchCreateForm table thead tr th.c-name').width() < 200) $('#batchCreateForm table thead tr th.c-name').width(200);
});

/* Get select of stories.*/
function setStories(moduleID, projectID, num)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=' + moduleID + '&storyID=0&num=' + num + '&type=short');
    $.get(link, function(stories)
    {
        var storyID = $('#story' + num).val();
        if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
        $('#story' + num).replaceWith(stories);
        if(moduleID == 0 || moduleID == 'ditto') $('#story' + num).append("<option value='ditto'>" + ditto + "</option>");
        $('#story' + num).val(storyID);
        if($('#zeroTaskStory').hasClass('zeroTask'))
        {
            $('#story' + num).find('option').each(function()
            {
                value = $(this).attr('value');
                if(value != 'ditto' && storyTasks[value] > 0)
                {
                    $(this).hide();
                    if(storyID == value) $('#story' + num).val('');
                }
            })
        }
        var chosenWidth = $("#story" + num + "_chosen").css('max-width');
        $("#story" + num + "_chosen").remove();
        $("#story" + num).next('.picker').remove();
        $("#story" + num).chosen();
        $("#story" + num + "_chosen").width(chosenWidth).css('max-width', chosenWidth);
    });
}

/* Copy story title as task title. */
function copyStoryTitle(num)
{
    var storyTitle = $('#story' + num).find('option:selected').text();
    startPosition  = storyTitle.indexOf(':') + 1;
    endPosition    = storyTitle.lastIndexOf('[');
    storyTitle     = storyTitle.substr(startPosition, endPosition - startPosition);

    $('#name\\[' + num + '\\]').val(storyTitle);
    $('#estimate\\[' + num + '\\]').val($('#storyEstimate' + num).val());
    $('#desc\\[' + num + '\\]').val(($('#storyDesc' + num).val()).replace(/<[^>]+>/g,'').replace(/(\n)+\n/g, "\n").replace(/^\n/g, '').replace(/\t/g, ''));

    var storyPri = $('#storyPri' + num).val();
    if(storyPri == 0) $('#pri' + num ).val('3');
    if(storyPri != 0) $('#pri' + num ).val(storyPri);
}

/* Set the story module. */
function setStoryRelated(num)
{
    var storyID = $('#story' + num).val();
    if(storyID && storyID != 'ditto')
    {
        var link = createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            $('#module' + num).val(parseInt(storyInfo.moduleID));
            $('#module' + num).trigger("chosen:updated");

            $('#storyEstimate' + num).val(storyInfo.estimate);
            $('#storyPri'      + num).val(storyInfo.pri);
            $('#storyDesc'     + num).val(storyInfo.spec);
        });

        storyLink  = createLink('story', 'view', "storyID=" + storyID);
        var concat = config.requestType != 'GET' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview' + num).removeAttr('disabled');
        $('#preview' + num).attr('href', storyLink);
    }
    else
    {
        storyLink  = '#';
        $('#preview' + num).attr('disabled', true);
        $('#preview' + num).attr('href', storyLink);
    }
}

/* Toggle zero task story. */
function toggleZeroTaskStory()
{
    var $toggle = $('#zeroTaskStory').toggleClass('checked');
    var zeroTask = $toggle.hasClass('checked');
    $.cookie('zeroTask', zeroTask, {expires:config.cookieLife, path:config.webRoot});
    $('select[name^="story"]').each(function()
    {
        var $select = $(this);
        var selectVal = $select.val();
        $select.find('option').each(function()
        {
            var $option = $(this);
            var value = $option.attr('value');
            $option.show();
            if(value != 'ditto' && storyTasks[value] > 0 && zeroTask)
            {
                $option.hide();
                if(selectVal == value) selectVal = '';
            }
        })
        $select.val(selectVal).trigger("chosen:updated");
    });
}

// see http://pms.zentao.net/task-view-5086.html
function markStoryTask()
{
    $('select[name^="story"]').each(function()
    {
        var $select = $(this);
        $select.find('option').each(function()
        {
            var $option = $(this);
            var value = $option.attr('value');
            var tasksCount = storyTasks[value];
            $option.attr('data-data', value).toggleClass('has-task', !!(tasksCount && tasksCount !== '0'));
        });
        $select.trigger("chosen:updated");
    });

    var getStoriesHasTask = function()
    {
        var storiesHasTask = {};
        $('#tableBody tbody>tr').each(function()
        {
            var $tr = $(this);
            if ($tr.find('input[name^="name"]').val())
            {
                storiesHasTask[$tr.find('select[name^="story"]').val()] = true;
            }
        });
        return storiesHasTask;
    };

    $('#batchCreateForm').on('chosen:showing_dropdown', 'select[name^="story"],.chosen-with-drop', function()
    {
        var storiesHasTask = getStoriesHasTask();
        var $container     = $(this).closest('td').find('.chosen-container');
        setTimeout(function()
        {
            $container.find('.chosen-results>li').each(function()
            {
                var $li = $(this);
                $li.toggleClass('has-new-task', !!storiesHasTask[$li.data('data')]);
            });
        }, 100);
    });
}

$(document).on('chosen:showing_dropdown', 'select[name^="story"],.chosen-with-drop', function()
{
    var select = $(this).closest('td').find('select');
    if($(select).val() == 'ditto')
    {
        var index = $(select).closest('td').index();
        var row   = $(select).closest('tr').index();
        var table = $(select).closest('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(select).val(value);
        $(select).trigger("chosen:updated");
        $(select).trigger("change");
    }
})
$(document).on('mousedown', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var table = $(this).closest('tr').parent();
        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = $(table).find('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }
        $(this).val(value);
    }
})

$(function()
{
    /* Adjust width for ie chosen width. */
    var chosenWidth = $('#module1_chosen').width();
    $('.chosen-container[id^=module]').width(chosenWidth);
    $('.chosen-container[id^=module]').css('max-width', chosenWidth);

    var chosenWidth = $('#story1_chosen').width();
    $('.chosen-container[id^=story]').width(chosenWidth);
    $('.chosen-container[id^=story]').css('max-width', chosenWidth);

    if($.cookie('zeroTask') == 'true') toggleZeroTaskStory();
    markStoryTask();

    if(storyID != 0) setStoryRelated(0);

    $(document).keydown(function(event)
    {
        if(event.ctrlKey && event.keyCode == 38)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('up');
        }
        else if(event.ctrlKey && event.keyCode == 40)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('down');
        }
        else if(event.keyCode == 38)
        {
            inputFocusJump('up');
        }
        else if(event.keyCode == 40)
        {
            inputFocusJump('down');
        }
    });
});
