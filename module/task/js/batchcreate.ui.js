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
            const $storyPicker = $storyTd.find('[name^=story]').zui('picker');
            $storyPicker.render({items: stories});
            $storyPicker.$.setValue(0);
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
    let $story      = $(event.target).closest('td').find('input[name^=story]');
    let $currentRow = $(event.target).closest('tr');
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

        if(storyID > 0)
        {
            $.getJSON(link, function(data)
            {
                const storyInfo = data['storyInfo'];

                $module.zui('picker').$.setValue(parseInt(storyInfo.moduleID), true);
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

$('#formSettingBtn').on('click', '.checkbox-primary [value=story]', function()
{
    $('#formSettingBtn .checkbox-primary [value=preview], #formSettingBtn .checkbox-primary [value=copyStory]').prop('checked', $('#formSettingBtn .checkbox-primary [value=story]').prop('checked'));
})

function checkBatchEstStartedAndDeadline(event)
{
    const $currentRow = $(event.target).closest('tr');
    const field       = $(event.target).closest('.form-batch-control').data('name');
    const estStarted  = $currentRow.find('[name^=estStarted]').val();
    const deadline    = $currentRow.find('[name^=deadline]').val();

    if(field == 'estStarted' && estStarted.length > 0 && estStarted < parentEstStarted)
    {
        const $estStartedTd = $currentRow.find('td[data-name=estStarted]');
        if($estStartedTd.find('.date-tip').length == 0 || $estStartedTd.find('.date-tip .form-tip').length > 0)
        {
            $estStartedTd.find('.date-tip').remove();

            let $datetip = $('<div class="date-tip"></div>');
            $datetip.append('<div class="form-tip text-warning">' + overParentEstStartedLang + '<span class="ignore-date underline">' + ignoreLang + '</div>');
            $dateTip.off('click', '.ignore-date').on('click', '.ignore-date', function(e){ignoreTip(e)});
            $estStartedTd.append($datetip);
        }
    }

    if(field == 'deadline' && deadline.length > 0 && deadline > parentDeadline)
    {
        const $deadlineTd = $currentRow.find('td[data-name=deadline]');
        if($deadlineTd.find('.date-tip').length == 0 || $deadlineTd.find('.date-tip .form-tip').length > 0)
        {
            $deadlineTd.find('.date-tip').remove();

            let $datetip = $('<div class="date-tip"></div>');
            $datetip.append('<div class="form-tip text-warning">' + overParentDeadlineLang + '<span class="ignore-date underline">' + ignoreLang + '</div>');
            $dateTip.off('click', '.ignore-date').on('click', '.ignore-date', function(e){ignoreTip(e)});
            $deadlineTd.append($datetip);
        }
    }
}
