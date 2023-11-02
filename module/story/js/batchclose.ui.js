window.renderRowData = function($row, index, story)
{
    let $title  = $row.find('.form-batch-input[data-name="title"]');
    if(story.twins)
    {
        $title.wrap("<div class='input-group'></div>");
        $title.after("<span class='input-group-addon'>" + langTwins + "<span class='text-secondary'>" + twinsCount[story.id] + "</span></span>");
    }

    if(story.status == 'draft')
    {
        $row.find('[data-name="closedReasonBox"]').find('.closedReason-select').on('inited', function(e, info)
        {
            let $closedReason = info[0];
            $closedReason.render({items: reasonList});
        });
    }

    $row.find('[data-name="closedReasonBox"]').find('.duplicate-select').on('inited', function(e, info)
    {
        let $duplicateStory = info[0];
        let link            = $.createLink('story', 'ajaxGetDuplicatedStory', 'storyID=' + story.id);
        $.getJSON(link, function(duplicateStoryList)
        {
            if(duplicateStoryList)
            {
                $duplicateStory.render({items: duplicateStoryList});
            }
        })
    });
};

window.toggleDuplicateBox = function(e)
{
    const $target     = $(e.target);
    const $currentRow = $target.closest('tr');
    $currentRow.find('[data-name="duplicateStory"]').toggleClass('hidden', $target.val() != 'duplicate');
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
