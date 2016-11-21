/* Remove width in defaultChosenOptions. */
delete defaultChosenOptions.width;

$(document).ready(removeDitto());//Remove 'ditto' in first row.

/* Get select of stories.*/
function setStories(moduleID, projectID, num)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=' + moduleID + '&storyID=0&num=' + num + '&type=short');
    $.get(link, function(stories)
    {
        var storyID = $('#story' + num).val();
        if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
        $('#story' + num).replaceWith(stories);
        if(moduleID == 0) $('#story' + num).append("<option value='ditto'>" + ditto + "</option>");
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
        $("#story" + num + "_chosen").remove();
        $("#story" + num).chosen(defaultChosenOptions);
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
}

/* Set the story module. */
function setStoryRelated(num)
{
    var storyID = $('#story' + num).val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetModule', 'storyID=' + storyID);
        $.get(link, function(moduleID)
        {
            $('#module' + num).val(moduleID);
            $('#module' + num).trigger("chosen:updated");
        });
    }
}

/* Toggle zero task story. */
function toggleZeroTaskStory()
{
    if($('#zeroTaskStory').hasClass('zeroTask'))
    {
        $('#zeroTaskStory').removeClass('zeroTask');
        zeroTask = false;
    }
    else
    {
        $('#zeroTaskStory').addClass('zeroTask');
        zeroTask = true;
    }
    $.cookie('zeroTask', zeroTask, {expires:config.cookieLife, path:config.webRoot});
    $('select[name^="story"]').each(function()
    {
        selectVal = $(this).val();
        $(this).find('option').each(function()
        {
            value = $(this).attr('value');
            $(this).show();
            if(value != 'ditto' && storyTasks[value] > 0 && zeroTask)
            {
              $(this).hide();
              if(selectVal == value) selectVal = '';
            }
        })
        $(this).val(selectVal);
        $(this).trigger("chosen:updated");
    })
}

$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
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

if(navigator.userAgent.indexOf("Firefox") < 0)
{
    $(document).on('input keyup paste change', 'textarea.autosize', function()
    {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight + 2) + "px"; 
    });
}

$(function()
{
    /* Adjust width for ie chosen width. */
    $('#module0_chosen').width($('#module1_chosen').width());
    $('#story0_chosen').width($('#story1_chosen').width());
    if($.cookie('zeroTask') == 'true') toggleZeroTaskStory();
})
