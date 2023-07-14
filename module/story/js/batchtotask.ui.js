window.renderRowData = function($row, index, row)
{
    index        += 1;
    $story        = $row.find('td.form-batch-control .form-batch-input[data-name=story]');
    $inputControl = $story.closest('.input-control');
    $inputControl.length == 0 ? $story.wrap("<div class='input-group'></div>") : $inputControl.wrap("<div class='input-group'></div>");

    viewLink    = typeof row != 'undefined' ? "href='" + $.createLink('story', 'view', "storyID=" + row.story) + "' data-toggle='modal' data-size='lg'" : "href='###' disabled=disabled";
    $inputGroup = $inputControl.length == 0 ? $story.closest('.input-group') : $inputControl.closest('.input-group');
    $row.find('td.form-batch-control .form-batch-input[data-name=module]').attr('onchange', 'setStories(this.value, ' + executionID + ', ' + index + ')');
    $story.attr('onchange', 'setStoryRelated(' + index + ')');
    $inputGroup.append("<span class='input-group-addon' style='padding:0px;'></span");
    $inputGroup.find('.input-group-addon').append("<a id='preview_" + index + "' " + viewLink + " class='btn ghost size-sm' title='" + langPreview + "'><i class='icon icon-eye'></i></a>");
    $inputGroup.find('.input-group-addon').append("<a href='javascript:copyStoryTitle(" + index + ")' class='btn ghost size-sm' title='" + copyStoryTitle + "'><i class='icon-arrow-right'></i></a>");
};

window.setStories = function(moduleID, executionID, index)
{
    var link = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=0&num=' + index + '&type=short');
    $.get(link, function(stories)
    {
        let $story      = $('[name=story\\[' + index + '\\]]');
        let $inputGroup = $story.closest('.input-group');
        let storyID     = $story.val();
        if(!stories) stories = '<select id="story_' + index + '" name="story[' + index + ']" class="form-control"></select>';
        $('[name=story\\[' + index + '\\]]').replaceWith(stories);
        $inputGroup.find('[name^=story]').attr('class', 'form-control form-batch-input').attr('data-name', 'story').attr('name', 'story[' + index + ']').attr('id', 'story_' + (index - 1)).attr('onchange', 'setStoryRelated(' + index + ')').val(storyID);
    });
};

window.setStoryRelated = function(index)
{
    var storyID = $('[name=story\\[' + index + '\\]]').val();
    if(storyID != 0)
    {
        storyLink  = $.createLink('story', 'view', "storyID=" + storyID);
        $('#preview_' + index).removeAttr('disabled');
        $('#preview_' + index).attr('data-toggle', 'modal');
        $('#preview_' + index).attr('data-size', 'lg');
        $('#preview_' + index).css('pointer-events', 'auto');
        $('#preview_' + index).attr('href', storyLink);
    }
    else
    {
        storyLink  = '###';
        $('#preview_' + index).attr('disabled', 'disabled');
        $('#preview_' + index).css('pointer-events', 'none');
        $('#preview_' + index).removeAttr('data-toggle');
        $('#preview_' + index).removeAttr('data-size');
        $('#preview_' + index).attr('href', storyLink);
    }
};

window.copyStoryTitle = function(index)
{
    var $story     = $('[name=story\\[' + index + '\\]]');
    var storyValue = $story.val();
    var storyTitle = $story.find('option[value="' + storyValue + '"]').text();

    var startPosition = storyTitle.indexOf(':') + 1;
    var endPosition   = storyTitle.lastIndexOf('[');
    if(endPosition > 0 && startPosition > 0) storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);

    $('[name=name\\[' + index + '\\]]').val(storyTitle);
}
