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
    if($row.length == 0) return;

    $.getJSON(getStoryLink, function(stories)
    {
        const $storyPicker = $row.find('[name^=story]').zui('picker');
        const storyID      = $row.find('[name^=story]').val();

        $storyPicker.render({items: stories})
        $storyPicker.$.setValue(storyID);
    });
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

    let $storyEstimate = $currentRow.find('.form-batch-input[data-name="storyEstimate"]');
    let $storyPri      = $currentRow.find('.form-batch-input[data-name="storyPri"]');
    let $storyDesc     = $currentRow.find('.form-batch-input[data-name="storyDesc"]');
    let $module        = $currentRow.find('input[name^="module"]');
    let $preview       = $currentRow.find('.form-batch-input[data-name="preview"] + button');

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

    startPosition = storyTitle.indexOf(':') + 1;
    endPosition   = storyTitle.lastIndexOf('[');
    if(endPosition < 0) endPosition = storyTitle.lastIndexOf('(');

    $currentRow.find('.form-batch-input[data-name="name"]').val(storyTitle.substr(startPosition, endPosition - startPosition));
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

$(document).off('change', '#formSettingBtn input[value=story]').on('change', '#formSettingBtn input[value=story]', function()
{
    const checked = $('#formSettingBtn input[value=story]').prop('checked');
    $('#formSettingBtn input[value=preview], #formSettingBtn input[value=copyStory]').prop('checked', checked);
})

function checkBatchEstStartedAndDeadline(event)
{
    if(taskDateLimit != 'limit') return;

    const $currentRow = $(event.target).closest('tr');
    const field       = $(event.target).closest('.form-batch-control').data('name');
    const estStarted  = $currentRow.find('[name^=estStarted]').val();
    const deadline    = $currentRow.find('[name^=deadline]').val();
    const level       = $currentRow.attr('data-level');

    let $nextRow = $currentRow.next();
    while($nextRow.length)
    {
        let nextLevel = $nextRow.attr('data-level');
        if(nextLevel <= level) break;

        if(field == 'estStarted') $nextRow.find('td[data-name=estStarted]').find('[id^=estStarted]').zui('datepicker').render({disabled: estStarted == ''});
        if(field == 'deadline') $nextRow.find('td[data-name=deadline]').find('[id^=deadline]').zui('datepicker').render({disabled: deadline == ''});

        $nextRow = $nextRow.next();
    }

    if($currentRow.find('td[data-name=name]').find('input[name^=name]').val() == '') return;

    const $estStartedTd = $currentRow.find('td[data-name=estStarted]');
    $estStartedTd.find('.date-tip').remove();
    if(field == 'estStarted' && estStarted.length > 0 && parentEstStarted.length > 0 && estStarted < parentEstStarted)
    {
        let $datetip = $('<div class="date-tip"></div>');
        $datetip.append('<div class="form-tip text-danger">' + overParentEstStartedLang + '</div>');
        $datetip.off('click', '.ignore-date').on('click', '.ignore-date', function(e){ignoreTip(e)});
        $estStartedTd.append($datetip);
    }

    const $deadlineTd = $currentRow.find('td[data-name=deadline]');
    $deadlineTd.find('.date-tip').remove();
    if(field == 'deadline' && deadline.length > 0 && parentDeadline.length > 0 && deadline > parentDeadline)
    {
        let $datetip = $('<div class="date-tip"></div>');
        $datetip.append('<div class="form-tip text-danger">' + overParentDeadlineLang + '</div>');
        $datetip.off('click', '.ignore-date').on('click', '.ignore-date', function(e){ignoreTip(e)});
        $deadlineTd.append($datetip);
    }
}

window.handleClickBatchFormAction = function(action, $row, rowIndex)
{
    if(action !== 'addSub' && action !== 'addSibling') return;

    if(!this.nestedLevelMap) this.nestedLevelMap = {};
    const level   = this.nestedLevelMap[$row.attr('data-gid')] || 0;
    const nextGid = this._idSeed++;
    this.nestedLevelMap[nextGid] = action === 'addSub' ? level + 1 : level;
    $row.find('input[data-name="estimate"]').prop('readonly', true); // 如果有子任务，不允许修改预计工时
    $row.find('input[data-name="isParent"]').val('1'); // 如果有子任务，该任务为父任务

    const nextLevel = level + 1;
    while(true)
    {
        $nextRow = $row.next();
        if($nextRow.length == 0 || $nextRow.attr('data-level') < nextLevel)
        {
            rowIndex = $nextRow.length == 0 ? $row.index() : $nextRow.index() - 1;
            break;
        }

        $row = $nextRow;
    }
    this.addRow(rowIndex, nextGid);
};

window.handleRenderRow = function($row, index)
{
    if(!this.nestedLevelMap) this.nestedLevelMap = {};

    /* 上一行： */
    const $prevRow = $row.prev();

    /* 添加序号。 */
    const $nameTd = $row.find('td[data-name="name"]');
    if($nameTd.find('.input-group').length == 0)
    {
        $nameTd.find('.input-control').wrap('<div class="input-group"></div>');
        $nameTd.find('.input-group').prepend('<div class="input-group-addon max-w-100px"></div>');
    }

    /* 从行中查找层级文本展示元素： */
    const nestedTextSelector = 'td[data-name="name"] .input-group-addon';

    /* 获取当前行的层级，下面可能会根据上一行层级修改当前行层级： */
    let level = this.nestedLevelMap[$row.attr('data-gid')] || 0;

    /* 当前行层级信息文本： */
    let text  = '1';

    /* 处理有上一行的情况： */
    if($prevRow.length)
    {
        /* 根据上一行层级，重新计算当前行层级：  */
        const prevLevel = +$prevRow.attr('data-level') || 0;
        if(prevLevel < level) level = prevLevel + 1;

        /* 根据上一行的层级文本，生成当前行的层级文本： */
        const prevText = $prevRow.find(nestedTextSelector).text();
        const parts    = prevText.split('.');
        if(prevLevel === level) parts[level] = +parts[level] + 1;
        else if(prevLevel > level)
        {
            parts.length = level + 1;
            parts[level] = +parts[level] + 1;
        }
        else parts[level] = 1;
        text = parts.join('.');
    }
    else
    {
        /* 如果没有上一行，当前行层级为 0： */
        level = 0;
    }

    /* 存储当前行层级信息： */
    this.nestedLevelMap[$row.attr('data-gid')] = level;
    $row.attr('data-level', level);

    /* 创建隐藏表单域用于向服务器提交当前行层级信息。 */
    $row.find(nestedTextSelector).attr('title', text).text(text).append(`<input type="hidden" data-name="level" name="level[${index + 1}]" value="${level}">`);
    $row.find('.form-batch-col-actions').addClass('is-pinned');
    if($prevRow.length && $prevRow.attr('data-level') >= level)
    {
        $prevRow.find('input[data-name="estimate"]').prop('readonly', false); // 如果没有子任务，重置预计字段的可编辑状态。
        $prevRow.find('input[data-name="isParent"]').val('0'); // 如果没有子任务，该任务不是父任务
    }
    if(edition == 'open' && (level > 0 || parentID)) $row.find('button[data-type=addSub]').attr('disabled', 'disabled');

    /* 复制上一行的人员下拉。*/
    $row.find('[data-name=assignedTo]').find('.picker-box').on('inited', function(e, info)
    {
        const $assignedTo    = info[0];
        const $preAssignedTo = $prevRow.find('input[name^=assignedTo]').zui('picker');
        if($preAssignedTo != undefined) $assignedTo.render({items: $preAssignedTo.options.items});
    })

    if(taskDateLimit == 'limit')
    {
        let disabledStarted  = false;
        let disabledDeadline = false;
        if(parentID)
        {
            disabledStarted  = parentEstStarted == '';
            disabledDeadline = parentDeadline == '';
        }
        else if(parentID == 0 && level > 0)
        {
            const $prevLevelRow      = $row.prevAll('tr[data-level="' + (level - 1) + '"]').first();
            const $prevLevelStarted  = $prevLevelRow.find('td[data-name=estStarted]').find('input[name^=estStarted]');
            const $prevLevelDeadline = $prevLevelRow.find('td[data-name=deadline]').find('input[name^=deadline]');
            disabledStarted  = $prevLevelStarted.val() == ''  || $prevLevelStarted.prop('disabled');
            disabledDeadline = $prevLevelDeadline.val() == '' || $prevLevelDeadline.prop('disabled');
        }
        $row.find('td[data-name=estStarted]').find('[id^=estStarted]').on('inited', function(e, info) { info[0].render({disabled: disabledStarted}); })
        $row.find('td[data-name=deadline]').find('[id^=deadline]').on('inited', function(e, info) { info[0].render({disabled: disabledDeadline}); })
    }
};

$(function()
{
    if(taskHasConsumed) zui.Modal.alert({message: langAddChildTask, closeBtn: false});
})
