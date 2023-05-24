/* Get select of stories.*/
function setStories()
{
    var moduleID = $(this).val();
    var index    = $(this).closest('tr').attr('data-index');
    var link     = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=0&num=' + index + '&type=short');
    $.get(link, function(stories)
    {
        var $story  = $('#story_' + index);
        var storyID = $story.val();
        $story.replaceWith(stories);
        if($('#zeroTaskStory').hasClass('checked'))
        {
            $story.find('option').each(function()
            {
                value = $(this).attr('value');
                if(value != 'ditto' && storyTasks[value] > 0)
                {
                    $(this).hide();
                    if(storyID == value) $story.val('');
                }
            })
        }
    });
}

/* Toggle zero task story. */
function toggleZeroTaskStory()
{
    var $toggle = $('#zeroTaskStory').toggleClass('checked');
    var zeroTask = $toggle.hasClass('checked');
    $.cookie.set('zeroTask', zeroTask, {expires:config.cookieLife, path:config.webRoot});
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

/* Set preview. */
function setStoryRelated()
{
    var storyID   = $(this).val();
    var index     = $(this).closest('tr').attr('data-index');
    var storyLink = '#';
    if(storyID != 0  && storyID != 'ditto')
    {
        var link = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            $('#module_' + index).val(parseInt(storyInfo.moduleID));

            $('#storyEstimate_' + index).val(storyInfo.estimate);
            $('#storyPri_'      + index).val(storyInfo.pri);
            $('#storyDesc_'     + index).val(storyInfo.spec);
        });

        storyLink = $.createLink('story', 'view', "storyID=" + storyID);
        $('#preview_' + index).removeAttr('disabled');
        $('#preview_' + index).css('pointer-events', 'auto');
        $('#preview_' + index).attr('href', storyLink);
    }
    else
    {
        $('#preview_' + index).attr('disabled', true);
        $('#preview_' + index).css('pointer-events', 'none');
        $('#preview_' + index).attr('href', storyLink);
    }
}

/* Copy story title as task title. */
function copyStoryTitle()
{
    var index      = $(this).closest('tr').attr('data-index');
    var storyTitle = $('#story_' + index).find('option:selected').text();
    var storyValue = $('#story_' + index).find('option:selected').val();

    if(storyValue === 'ditto')
    {
        for(var i = index; i <= index && i >= 1; i--)
        {
            var selectedValue = $('select[id="story_' + i +'"]').val();
            var selectedTitle = $('select[id="story_' + i +'"]').find('option:selected').text();
            if(selectedValue !== 'ditto')
            {
                storyTitle = selectedTitle;
                break;
            }
        }
    }

    startPosition  = storyTitle.indexOf(':') + 1;
    endPosition    = storyTitle.lastIndexOf('[');
    storyTitle     = storyTitle.substr(startPosition, endPosition - startPosition);

    $('#name_' + index).val(storyTitle);
    $('#estimate_' + index).val($('#storyEstimate_' + index).val());
    $('#desc_' + index).val(($('#storyDesc_' + index).val()).replace(/<[^>]+>/g,'').replace(/(\n)+\n/g, "\n").replace(/^\n/g, '').replace(/\t/g, ''));

    var storyPri = $('#storyPri_' + index).val();
    if(storyPri == 0) $('#pri_' + index ).val('3');
    if(storyPri != 0) $('#pri_' + index ).val(storyPri);
}

/* Load lanes. */
function loadLanes()
{
    var regionID = $(this).val();
    console.log(regionID);
    var index    = $(this).closest('tr').attr('data-index');
    var laneLink = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=task&field=lanes&i=' + index);
    $.get(laneLink, function(lanes)
    {
        $('#lanes_' + index).replaceWith(lanes);
    });
}
