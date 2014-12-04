var storyChosenOptions = $.extend({}, defaultChosenOptions, {drop_width: 400, width: '200px'});

$(function() {
    for(i = 0; i < batchCreateNum; i++) $("#story" + i).chosen(storyChosenOptions);
});

/* Get select of stories.*/
function setStories(moduleID, projectID, num)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&moduleID=' + moduleID + '&storyID=0&num=' + num + '&type=short');
    $.get(link, function(stories)
    {
        var storyID = $('#story' + num).val();
        if(!stories) stories = '<select id="story[' + num + ']" name="story' + num + '" class="select-1"></select>';
        $('#story' + num).replaceWith(stories);
        if(moduleID == 0) $('#story' + num).append("<option value='ditto'>" + ditto + "</option>")
        $('#story' + num).val(storyID);
        $('#story' + num + '_chosen').remove();
        $("#story" + num).chosen(storyChosenOptions);
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

$(document).on('click', '.chosen-with-drop', function()
{
    var select = $(this).prev('select');
    if($(select).val() == 'ditto')
    {
        var index = $(select).parents('td').index();
        var row   = $(select).parents('tr').index();
        var table = $(select).parents('tr').parent();
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
        var index = $(this).parents('td').index();
        var row   = $(this).parents('tr').index();
        var table = $(this).parents('tr').parent();
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
