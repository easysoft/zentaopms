window.renderRowData = function($row, index, story)
{
    var $title  = $row.find('.form-batch-input[data-name="title"]');
    var $status = $row.find('.form-batch-input[data-name="status"]');

    $title.attr('disabled', 'disabled').attr('title', story.title).after("<input type='hidden' name='title[" + story.id + "]' value='" + story.title + "' />");
    $status.attr('disabled', 'disabled');

    if(story.twins)
    {
        $title.wrap("<div class='input-group'></div>");
        $title.after("<span class='input-group-addon'>" + langTwins + "<span class='text-secondary'>" + twinsCount[story.id] + "</span></span>");
    }

    var $closedReason = $row.find('.form-batch-input[data-name="closedReason"]');
    $closedReason.attr('onchange', 'setDuplicateAndChild(this)').wrap("<div class='input-group'></div>");

    var appendStoryHtml = "<span class='duplicateStoryBox" + (story.closedReason != 'duplicate' ? " hidden" : '') + "'>";
    appendStoryHtml += "<select class='form-control form-batch-input' name='duplicateStory[" + story.id + "]' id='duplicateStory_" + index + "' data-name='duplicateStory' data-id='" + story.id + "' onmouseenter='getDuplicateStories(this)'>";
    appendStoryHtml += "<option value=''></option>";
    appendStoryHtml += '</select></span>';
    $closedReason.after(appendStoryHtml);

    if(story.status == 'draft') $closedReason.find('option[value="cancel"]').remove();
};

window.setDuplicateAndChild = function(obj)
{
    var $this = $(obj);
    $this.closest('.input-group').find('.duplicateStoryBox').toggleClass('hidden', $this.val() != 'duplicate');
};

window.getDuplicateStories = function(obj)
{
    var $this   = $(obj);
    var options = $this.find('option').length;
    if(options <= 1)
    {
        var storyID = $this.data('id');
        var link    = $.createLink('story', 'ajaxGetDuplicatedStory', 'storyID=' + storyID);

        $this.closest('.duplicateStoryBox').load(link, function()
        {
            $(this).find('select').addClass('form-batch-input').attr('data-name', 'duplicateStory');
        });
    }
};
