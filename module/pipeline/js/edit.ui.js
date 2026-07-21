/**
 * Edit pipeline page JS.
 *
 * @package pipeline
 */

/**
 * 添加自定义构建参数行。
 * Add a custom param row.
 */
window.addParam = function()
{
    var paramName  = langParamName;
    var paramValue = langParamValue;
    var deleteText = langDelete;
    var tbody      = $('#paramTbody');
    var keyInput   = $('<input>').attr({type: 'text', name: 'paramKey[]', 'class': 'form-control', placeholder: paramName, maxlength: 50})
        .on('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
        });
    var valInput   = $('<input>').attr({type: 'text', name: 'paramValue[]', 'class': 'form-control', placeholder: paramValue});
    var delBtn     = $('<button>').attr({type: 'button', title: deleteText}).addClass('btn ghost text-danger size-sm del-param')
        .append($('<i>').addClass('icon icon-trash'))
        .on('click', function() { window.deleteParam(this); });
    var row = $('<tr>').addClass('param-row').append(
        $('<td>').append(keyInput),
        $('<td>').append(valInput),
        $('<td>').addClass('text-center').append(delBtn)
    );
    tbody.append(row);
    keyInput.trigger('focus');
};

/**
 * 删除自定义构建参数行。
 * Delete a custom param row.
 */
window.deleteParam = function(e)
{
    $(e).closest('tr').remove();
};

/**
 * 重置触发器弹窗为新建模式。
 * Reset trigger modal to add mode.
 */
window.resetTriggerModal = function()
{
    window._editTriggerField = null;
    window._editTriggerID    = null;

    /* Update available trigger type options dynamically */
    window.updateTriggerTypeOptions();

    /* Clear form fields. */
    $('[name="event"]').val('').trigger('change');
    $('[name="comment"]').val('');
    $('[name="weekDay"]').val('').trigger('change');
    $('[name="time"]').val('');
    $('[name="monthDay"]').val('').trigger('change');

    /* Reset modal title. */
    $('#triggerModal .modal-title').text(addTriggerTitle);
};

/**
 * 根据触发方式切换显示对应字段。
 * Toggle trigger fields based on selected type.
 */
window.changeTriggerType = function()
{
    var type = $('[name="type"]:checked').val();
    $('#eventRow').toggleClass('hidden', type !== 'event');
    $('#weekDayRow').toggleClass('hidden', type !== 'week');
    $('#timeRow').toggleClass('hidden', type !== 'week' && type !== 'month');
    $('#monthDayRow').toggleClass('hidden', type !== 'month');
    $('#commentRow').toggleClass('hidden', type !== 'comment');
};

/**
 * 修改触发器：根据字段预填弹窗并显示。
 * Edit trigger: pre-fill modal based on field and show.
 */
window.editTrigger = function(e)
{
    var $btn      = $(e.target || e);
    var triggerID = $btn.attr('data-trigger-id');
    var field     = $btn.attr('data-field');
    if(!triggerID || !field) return;

    window._editTriggerField = field;
    window._editTriggerID    = triggerID;

    /* Show all radio options first, then hide irrelevant ones. */
    $('[name="type"]').closest('label').show();

    if(field === 'event')
    {
        /* Only show "事件" option. */
        $('[name="type"][value="week"]').closest('label').hide();
        $('[name="type"][value="month"]').closest('label').hide();
        $('[name="type"][value="comment"]').closest('label').hide();

        $('[name="type"][value="event"]').prop('checked', true).trigger('change');

        /* Pre-fill event picker. */
        var rawValue = $btn.attr('data-raw-value');
        if(rawValue)
        {
            var values = rawValue.split(',');
            $('[name="event"]').val(values).trigger('change');
        }
    }
    else if(field === 'comment')
    {
        /* Only show "关键字" option. */
        $('[name="type"][value="event"]').closest('label').hide();
        $('[name="type"][value="week"]').closest('label').hide();
        $('[name="type"][value="month"]').closest('label').hide();

        $('[name="type"][value="comment"]').prop('checked', true).trigger('change');

        /* Pre-fill comment input. */
        $('[name="comment"]').val($btn.attr('data-value'));
    }
    else if(field === 'cron')
    {
        /* Show "按周" and "按月", hide "事件" and "关键字". */
        $('[name="type"][value="event"]').closest('label').hide();
        $('[name="type"][value="comment"]').closest('label').hide();

        var cronType = $btn.attr('data-cron-type');
        var cron     = $btn.attr('data-cron');

        $('[name="type"][value="' + cronType + '"]').prop('checked', true).trigger('change');

        /* Parse cron and pre-fill fields. */
        var parts = cron.split(' ');
        if(parts.length === 5)
        {
            var minute  = parts[0];
            var hour    = parts[1];
            /* parts[2] = day or *, parts[3] = month or *, parts[4] = weekDay or * */

            $('[name="time"]').val(hour + ':' + minute);

            if(cronType === 'week')
            {
                $('[name="weekDay"]').val(parts[4]).trigger('change');
            }
            else
            {
                $('[name="monthDay"]').val(parts[2]).trigger('change');
            }
        }
    }

    /* Change modal title. */
    $('#triggerModal .modal-title').text(editTriggerTitle);

    /* Show modal. */
    zui.Modal.open({target: '#triggerModal'});
};

/**
 * 根据当前已有触发器类型，计算可添加的触发器类型列表。
 * Calculate available trigger types based on existing triggers.
 */
/**
 * 当前可用的触发器类型缓存，由 refreshTriggerGroup 更新。
 * Cached available trigger types, updated by refreshTriggerGroup.
 */
var _cachedAvailableTypes = null;

window.getAvailableTriggerTypes = function()
{
    if(_cachedAvailableTypes) return _cachedAvailableTypes;

    var isJenkins = (pipelineEngine === 'jenkins');

    /* 初始从 DOM 读取 */
    var hasEvent   = $('#triggerTbody .del-trigger[data-field="event"]').length > 0;
    var hasCron    = $('#triggerTbody .del-trigger[data-field="cron"]').length > 0;
    var hasComment = $('#triggerTbody .del-trigger[data-field="comment"]').length > 0;

    var available = [];
    if(!isJenkins && !hasEvent)   available.push('event');
    if(!hasCron)    { available.push('week'); available.push('month'); }
    if(!isJenkins && !hasComment) available.push('comment');

    _cachedAvailableTypes = available;
    return available;
};

/**
 * 更新modal中的触发器类型radioList选项。
 * Update trigger type radio options in modal.
 */
window.updateTriggerTypeOptions = function()
{
    var available = window.getAvailableTriggerTypes();
    var $typeRadios = $('[name="type"]');

    /* Hide all radio options first */
    $typeRadios.closest('label').hide();

    /* Show only available types */
    available.forEach(function(type) {
        $('[name="type"][value="' + type + '"]').closest('label').show();
    });

    /* Select first available type */
    if(available.length > 0)
    {
        var firstType = available[0];
        $('[name="type"][value="' + firstType + '"]').prop('checked', true).trigger('change');
    }
};

/**
 * 局部刷新触发器区域并同步状态。
 * Partial refresh the trigger group and sync flags.
 */
window.refreshTriggerGroup = function()
{
    loadPage({
        url:      $.createLink('pipeline', 'edit', 'id=' + pipelineID),
        selector: '#triggerGroup>*,#triggerModal',
        partial:  true,
        success:  function() {
            setTimeout(function() {
                /* 重新绑定 radio 切换事件（document 级委托，避免 #triggerModal 被替换后失效） */
                $(document).off('change.triggerType', '#triggerModal [name="type"]').on('change.triggerType', '#triggerModal [name="type"]', window.changeTriggerType);

                var isJenkins = (pipelineEngine === 'jenkins');

                var hasEvent   = $('#triggerTbody .del-trigger[data-field="event"]').length > 0;
                var hasCron    = $('#triggerTbody .del-trigger[data-field="cron"]').length > 0;
                var hasComment = $('#triggerTbody .del-trigger[data-field="comment"]').length > 0;

                var available = [];
                if(!isJenkins && !hasEvent)   available.push('event');
                if(!hasCron)    { available.push('week'); available.push('month'); }
                if(!isJenkins && !hasComment) available.push('comment');
                _cachedAvailableTypes = available;

                var canAdd = isJenkins ? !hasCron : !(hasEvent && hasComment && hasCron);
                if(canAdd) $('#addTriggerBtn').removeClass('hidden');
                else $('#addTriggerBtn').addClass('hidden');
            }, 0);
        }
    });
};

/**
 * 删除触发器行。
 * Delete a trigger row.
 */
window.deleteTrigger = function(e)
{
    var $btn  = $(e.target || e);
    var triggerID = $btn.attr('data-trigger-id');
    var field     = $btn.attr('data-field');
    if(!triggerID) return;

    $.post($.createLink('pipeline', 'ajaxDeleteTrigger', 'triggerID=' + triggerID + '&field=' + field), function()
    {
        window.refreshTriggerGroup();
    });
};

$(function()
{
    /* 限制参数名只能输入字母数字下划线。 */
    $(document).on('input', '[name="paramKey[]"]', function()
    {
        this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
    });

    /* 删除自定义构建参数。 */
    $(document).on('click', '.del-param', function()
    {
        window.deleteParam(this);
    });

    /* 删除触发器。 */
    $(document).on('click', '.del-trigger', function()
    {
        window.deleteTrigger(this);
    });

    /* 监听triggerModal打开事件，自动初始化表单 */
    $('#triggerModal').on('shown.zui.modal', function()
    {
        /* Only reset for add mode, not edit mode */
        if(!window._editTriggerField && !window._editTriggerID)
        {
            window.resetTriggerModal();
        }
    });
});
