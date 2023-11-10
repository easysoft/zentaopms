window.renderRowData = function($row, index, row)
{
    index += 1;
    $row.find('[data-name="story"]').find('.picker-box').on('inited', function(e, info)
        {
            let $story = $(info[0].element);
            $story.closest('.form-batch-ditto-wrapper').length == 0 ? $story.css('width', 'calc(100% - 60px)').wrap("<div class='input-group'></div>") : $story.closest('.form-batch-ditto-wrapper').css('width', 'calc(100% - 60px)').wrap("<div class='input-group'></div>");

            let $inputControl = $story.closest('.input-group');
            let viewLink    = typeof row != 'undefined' && row.story != 0 ? "href='" + $.createLink('story', 'view', "storyID=" + row.story) + "' data-toggle='modal' data-size='lg'" : "href='###' disabled=disabled";
            let $inputGroup    = $("<span class='flex items-center' style='padding:0px;'></span>");
            $inputGroup.append("<a id='preview_" + index + "' " + viewLink + " class='btn ghost size-sm' title='" + langPreview + "'><i class='icon icon-eye'></i></a>");
            $inputGroup.append("<a class='copy-title-btn btn ghost size-sm' title='" + copyStoryTitleTip + "'><i class='icon-arrow-right'></i></a>");
            $inputControl.append($inputGroup[0]);
        });
};

function setStories(event)
{
    const $target  = $(event.target);
    const moduleID = $target.val();
    const $row     = $target.closest('tr');
    const link = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=0&pageType=&type=short');
    $.getJSON(link, function(stories)
    {
        let $story      = $row.find('[name^=story]');
        let storyID     = $story.val();
        let $storyPicker = $story.zui('picker');
        $storyPicker.render({items: stories});
        $storyPicker.$.setValue(storyID);
    });
};

function setStoryRelated(event)
{
    const $target = $(event.target);
    const storyID = $target.val();
    const index   = parseInt($target.closest('tr').attr('data-index')) + 1;
    if(storyID != 0)
    {
        var storyLink  = $.createLink('story', 'view', "storyID=" + storyID);
        $('#preview_' + index).removeAttr('disabled');
        $('#preview_' + index).attr('data-toggle', 'modal');
        $('#preview_' + index).attr('data-size', 'lg');
        $('#preview_' + index).css('pointer-events', 'auto');
        $('#preview_' + index).attr('href', storyLink);
    }
    else
    {
        var storyLink  = '###';
        $('#preview_' + index).attr('disabled', 'disabled');
        $('#preview_' + index).css('pointer-events', 'none');
        $('#preview_' + index).removeAttr('data-toggle');
        $('#preview_' + index).removeAttr('data-size');
        $('#preview_' + index).attr('href', storyLink);
    }
};

function copyStoryTitle(event)
{
    var $target       = $(event.target);
    var $story        = $target.closest('.form-batch-control');
    var storyTitle    = $story.find('.picker span.picker-single-selection').text();
    var startPosition = storyTitle.indexOf(':') + 1;
    var endPosition   = storyTitle.lastIndexOf('[');
    if(endPosition > 0 && startPosition > 0) storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);

    $($story.closest('tr').find('[name^=name]')).val(storyTitle);
}
