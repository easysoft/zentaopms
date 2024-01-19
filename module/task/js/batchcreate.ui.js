$(function()
{
    if(taskConsumed > 0) zui.Modal.alert(addChildTask);
})

$('#zeroTaskStory').closest('.panel-actions').addClass('row-reverse justify-between w-11/12');
$('#zeroTaskStory').closest('.checkbox-primary').addClass('items-center');

/**
 * Get select of stories.
 *
 * @access public
 * @return void
 */
function setStories(event)
{
    const $module      = $(event.target);
    const $currentRow  = $module.closest('tr');
    const moduleID     = $module.val();
    const getStoryLink = $.createLink('task', 'ajaxGetStories', 'executionID=' + executionID + '&moduleID=' + moduleID + '&zeroTaskStory=' + $('#zeroTaskStory').hasClass('checked'));

    let $row = $currentRow;
    while($row.length)
    {
        const $storyPicker = $row.find('[name^=story]').zui('picker');
        const storyID      = $row.find('[name^=story]').val();
        $.getJSON(getStoryLink, function(stories)
        {
            $storyPicker.render({items: stories})
            $storyPicker.$.setValue(storyID);
        });

        $row = $row.next('tr');
        if(!$row.find('td[data-name="module"][data-ditto="on"]').length) break;
    }
}

/**
 * Toggle zero task story.
 *
 * @access public
 * @return void
 */
function toggleZeroTaskStory()
{
    let $toggle = $('#zeroTaskStory').toggleClass('checked');
    let zeroTask = $toggle.hasClass('checked');
    $.cookie.set('zeroTask', zeroTask, {expires:config.cookieLife, path:config.webRoot});

    $('td[data-name="story"]').each(function()
    {
        const moduleID     = $(this).closest('tr').find('input[name^=module]').val();
        const getStoryLink = $.createLink('task', 'ajaxGetStories', 'executionID=' + executionID + '&moduleID=' + moduleID + '&zeroTaskStory=' + zeroTask);
        const $storyTd     = $(this);
        $.getJSON(getStoryLink, function(stories)
        {
            $storyTd.find('[name^=story]').zui('picker').render({items: stories});
        });
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
    let $story      = $(event.target);
    let $currentRow = $story.closest('tr');
    let storyID     = $story.val();
    let link        = $.createLink('story', 'ajaxGetInfo', 'storyID=' + storyID + '&pageType=batch');
    let $row        = $currentRow;

    while($row.length)
    {
        let $storyEstimate = $row.find('.form-batch-input[data-name="storyEstimate"]');
        let $storyPri      = $row.find('.form-batch-input[data-name="storyPri"]');
        let $storyDesc     = $row.find('.form-batch-input[data-name="storyDesc"]');
        let $module        = $row.find('input[name^="module"]');
        let $preview       = $row.find('.form-batch-input[data-name="preview"] + button');

        if(storyID)
        {
            $.getJSON(link, function(data)
            {
                const storyInfo = data['storyInfo'];

                $module.zui('picker').$.setValue(parseInt(storyInfo.moduleID));
                $storyEstimate.val(storyInfo.estimate);
                $storyPri.val(storyInfo.pri);
                $storyDesc.val(storyInfo.spec);

                $preview.removeClass('disabled');
                $preview.css('pointer-events', 'auto');
                $preview.attr('data-url', $.createLink('story', 'view', "storyID=" + storyID));
            });
        }
        else
        {
            $storyEstimate.val('');
            $storyPri.val(3);
            $storyDesc.val('');

            $preview.addClass('disabled');
            $preview.css('pointer-events', 'none');
            $preview.attr('data-url', '#');
        }

        $row = $row.next('tr');
        if(!$row.find('td[data-name="story"][data-ditto="on"]').length) break;
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
    const $story         = $currentRow.find('td[data-name="story"]');
    const $storyEstimate = $currentRow.find('.form-batch-input[data-name="storyEstimate"]');
    const $storyPri      = $currentRow.find('.form-batch-input[data-name="storyPri"]');
    const $storyDesc     = $currentRow.find('.form-batch-input[data-name="storyDesc"]');
    let   storyTitle     = $story.find('.picker-single-selection').text();

    startPosition  = storyTitle.indexOf(':') + 1;
    endPosition    = storyTitle.lastIndexOf('[');
    storyTitle     = storyTitle.substr(startPosition, endPosition - startPosition);

    $currentRow.find('.form-batch-input[data-name="name"]').val(storyTitle);
    $currentRow.find('.form-batch-input[data-name="estimate"]').val($storyEstimate.val());
    $currentRow.find('input[name^="pri"]').zui('pripicker').$.setValue($storyPri.val() ? $storyPri.val() : 0);
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
    let $row = $currentRow;
    while($row.length)
    {
        const $lanePicker = $row.find('[name^=lane]').zui('picker');
        $.getJSON(laneLink, function(lanes)
        {
            $lanePicker.render({items: lanes})
            $lanePicker.$.setValue(lanes[0].value);
        });

        $row = $row.next('tr');
        if(!$row.find('td[data-name="region"][data-ditto="on"]').length) break;
    }
}
