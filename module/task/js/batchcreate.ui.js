/**
 * Get select of stories.
 *
 * @access public
 * @return void
 */
function setStories(event)
{
    const $module     = $(event.target);
    const $currentRow = $module.closest('tr');
    const moduleID    = $module.val();
    const link        = $.createLink('story', 'ajaxGetExecutionStories', 'executionID=' + executionID + '&productID=0&branch=all&moduleID=' + moduleID + '&storyID=0&pageType=batch&type=short');
    $.getJSON(link, function(stories)
    {
        if(!stories) return;
        let $row = $currentRow;
        while($row.length)
        {
            $story = $row.find('.form-batch-input[data-name="story"]').empty();
            $.each(stories, function(index, story)
            {
                if(story.value && $('#zeroTaskStory').hasClass('checked') && storyTasks[story.value] > 0) return;
                $story.append('<option value="' + story.value + '">' + story.text + '</option>');
            });

            $row = $row.next('tr');
            if(!$row.find('td[data-name="story"][data-ditto="on"]').length) break;
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

/**
 * Set preview.
 *
 * @access public
 * @return void
 */
function setStoryRelated(event)
{
    const $story         = $(event.target);
    const $currentRow    = $story.closest('tr');
    const storyID        = $story.val();
    const $storyEstimate = $currentRow.find('.form-batch-input[data-name="storyEstimate"]');
    const $storyPri      = $currentRow.find('.form-batch-input[data-name="storyPri"]');
    const $storyDesc     = $currentRow.find('.form-batch-input[data-name="storyDesc"]');
    const $module        = $currentRow.find('.form-batch-input[data-name="module"]');
    const $preview       = $currentRow.find('.form-batch-input[data-name="preview"]');
    if(storyID)
    {
        var link = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(data)
        {
            const storyInfo = data['storyInfo'];
            $module.val(parseInt(storyInfo.moduleID));

            $storyEstimate.val(storyInfo.estimate);
            $storyPri.val(storyInfo.pri);
            $storyDesc.val(storyInfo.spec);
        });

        $preview.removeAttr('disabled');
        $preview.css('pointer-events', 'auto');
        $preview.attr('href', $.createLink('story', 'view', "storyID=" + storyID));
    }
    else
    {
        $storyEstimate.val('');
        $storyPri.val(3);
        $storyDesc.val('');

        $preview.attr('disabled', true);
        $preview.css('pointer-events', 'none');
        $preview.attr('href', '#');
    }
}

/**
 * Copy story title as task title.
 *
 * @access public
 * @return void
 */
function copyStoryTitle(event)
{
    const $currentRow    = $(event.target).closest('tr');
    const $story         = $currentRow.find('.form-batch-input[data-name="story"]');
    const $storyEstimate = $currentRow.find('.form-batch-input[data-name="storyEstimate"]');
    const $storyPri      = $currentRow.find('.form-batch-input[data-name="storyPri"]');
    const $storyDesc     = $currentRow.find('.form-batch-input[data-name="storyDesc"]');
    const storyValue     = $story.val();
    var   storyTitle     = $story.find('option[value="' + storyValue + '"]').text();

    startPosition  = storyTitle.indexOf(':') + 1;
    endPosition    = storyTitle.lastIndexOf('[');
    storyTitle     = storyTitle.substr(startPosition, endPosition - startPosition);

    $currentRow.find('.form-batch-input[data-name="name"]').val(storyTitle);
    $currentRow.find('.form-batch-input[data-name="estimate"]').val($storyEstimate.val());
    $currentRow.find('.form-batch-input[data-name="pri"]').val($storyPri.val() ? $storyPri.val() : 0);
    $currentRow.find('.form-batch-input[data-name="desc"]').val(($storyDesc.val()).replace(/<[^>]+>/g,'').replace(/(\n)+\n/g, "\n").replace(/^\n/g, '').replace(/\t/g, ''));
}

/**
 * Load lanes.
 *
 * @access public
 * @return void
 */
function loadLanes(event)
{
    const regionID    = $(event.target).val();
    const $currentRow = $(event.target).closest('tr');
    const laneLink    = $.createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=task&field=lanes&pageType=batch');
    $.getJSON(laneLink, function(lanes)
    {
        $lane = $currentRow.find('.form-batch-input[data-name="lane"]').empty();
        if(!lanes) return;
        $.each(lanes, function(index, lane)
        {
            $lane.append('<option value="' + lane.value + '">' + lane.text + '</option>');
        });

    });
}
