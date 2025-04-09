window.handleClickBatchFormAction = function(action, $row, rowIndex)
{
    if(action !== 'addSub' && action !== 'addSibling') return;

    if(!this.nestedLevelMap) this.nestedLevelMap = {};
    const level   = this.nestedLevelMap[$row.attr('data-gid')] || 0;
    const nextGid = this._idSeed++;
    this.nestedLevelMap[nextGid] = action === 'addSub' ? level + 1 : level;
    $row.find('input[data-name="estimate"]').prop('readonly', true); // 如果有子任务，不允许修改预计工时

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

window.handleRenderRow = function($row, index, data)
{
    if(!this.nestedLevelMap) this.nestedLevelMap = {};

    /* 上一行： */
    const $prevRow = $row.prev();

    /* 添加序号。 */
    const $nameTd = $row.find('td[data-name="name"]');
    if($nameTd.find('.input-group').length == 0)
    {
        $nameTd.find('input.form-control').wrap('<div class="input-group"></div>');
        $nameTd.find('.input-group').prepend('<div class="input-group-addon max-w-100px"></div>');
    }

    /* 从行中查找层级文本展示元素： */
    const nestedTextSelector = 'td[data-name="name"] .input-group-addon';

    /* 获取当前行的层级，下面可能会根据上一行层级修改当前行层级： */
    let level = this.nestedLevelMap[$row.attr('data-gid')] || 0;
    if(typeof data != 'undefined' && typeof data.grade != 'undefined') level = data.grade - planGrade;

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
    $row.find(nestedTextSelector).attr('title', text).text(text).append(`<input type="hidden" name="level[${index + 1}]" value="${level}">`);
    $row.find('.form-batch-col-actions').addClass('is-pinned');

    if(typeof data != 'undefined' && typeof data.id != 'undefined') $row.find('[data-name="ACTIONS"]').find('[data-type="delete"]').addClass('hidden'); //隐藏已有数据的删除按钮。
    if($row.find('input[data-name="milestone"]:checked').length == 0) $row.find('input[data-name="milestone"]').eq(1).prop('checked', true);

    if(project.model == 'ipd' && planID == '0' && data)
    {
        const $attribute = data.attribute;
        const $point     = $row.find('[data-name="point"]');

        $point.find('.picker-box').on('inited', function(e, info)
        {
            let $picker = info[0];
            let options = $picker.options;
            let items   = [{text: '', value: ''}];
            for(let point in ipdStagePoint[$attribute])
            {
                let disabled = false
                let $value   = ipdStagePoint[$attribute][point];
                let hint     = $value;

                /* 如果已经评审过，则置灰不能选。*/
                if(reviewedPoints[$value] !== undefined && reviewedPoints[$value].disabled)
                {
                    disabled = true;
                    hint     = reviewedPointTip;
                }

                items.push({text: $value, value: $value, disabled: disabled, hint: hint});
            }
            options.items = items;

            $picker.render(options);
        });

        if(data.hasOwnProperty('status') && data.status != 'wait')
        {
            $row.find('[data-name=enabled] input').attr('disabled', 'disabled');
            $row.find('[data-name=enabled]').attr('title', cropStageTip);
        }

        if(data.enabled == 'off')
        {
            /* 需要等该行所有input元素加载完再执行changeEnabled方法 */
            window.waitDom("tr[data-index='" + index + "'] [data-name='PM'] input", function(){changeEnabled($row.find('[data-name=enabled] [name^=enabled]'));});
        }

        window.waitDom("div.picker-multi-selection", function()
        {
            let selection = $row.find('div.picker-multi-selection');
            selection.each(function(index, element) {
                /* 获取title。*/
                let $title = $(element).attr('title');

                /* 如果该评审点以提交评审，则不能取消选择。*/
                if(reviewedPoints[$title] !== undefined && reviewedPoints[$title].disabled)
                {
                    $(element).attr('title', reviewedPointTip); // 修改title
                    $(element).find(".picker-deselect-btn").remove(); // 禁用点击事件
                }
            });
        });
    }

    if(level > 0 || planGrade > 1)
    {
        let preAttribute = $prevRow ? $prevRow.find('[name^=attribute]').val() : '';
        $row.find('[data-name="attribute"]').find('.picker-box').on('inited', function(e, info)
        {
            let $attributePicker = info[0];

            let disabled = false;
            if(preAttribute && preAttribute != 'mix') disabled = true;
            if(data && data.attribute != 'mix') disabled = true;

            if(disabled) $attributePicker.render({disabled: disabled});
            if(preAttribute) $attributePicker.$.setValue(preAttribute);
        });
    }

    if(data != undefined && data.hasOwnProperty('type'))
    {
        $row.find('[data-name="type"]').find('.picker-box').on('inited', function(e, info)
        {
            let $type = info[0];
            $type.render({disabled: true});
        });
    }

    if(data != undefined && data.disabled)
    {
        let name  = '';
        let value = '';
        const checkBoxList = $row.children('[data-name=milestone]').find('input');
        Object.values(checkBoxList).forEach(function(ele)
        {
            if(typeof ele !== 'object') return;

            name = ele.name;
            if(ele.checked) value = ele.value;
        });

        checkBoxList.attr('disabled', 'disabled');

        /* Append hidden input element for passing value. */
        let inputEle = document.createElement('input');
        inputEle.setAttribute('name',  name);
        inputEle.setAttribute('value', value);
        inputEle.setAttribute('type',  'hidden');
        $row.children('[data-name=milestone]').append(inputEle);
    }
};

window.onChangeExecutionType = function(event)
{
    loadPage($.createLink('programplan', 'create', `projectID=${projectID}&productID=${productID}&planID=${planID}&type=` + $(event.target).val() + `&from=&syncData=${syncData}`));
};

window.onChangeParallel = function(event)
{
    const parallel = $(event.target).val();
    $('input[name^="parallel"]').val(parallel);
}

/**
 * Add row errors.
 *
 * @param  array  $rowErrors
 * @access public
 * @return void
 */
window.addRowErrors = function(rowErrors)
{
    var errorFieldID, errorTip, errorHTML;
    $('.text-danger.help-text').remove();
    $('#dataform input').removeClass('has-error');
    var index = 0;
    var alterError = '';
    $('input[name^=name]').each(function()
    {
        if($(this).val() == '') return true;

        if(typeof rowErrors[index] == 'object')
        {
            for(var errorField in rowErrors[index])
            {
                $errorTD  = $(this).closest('tr').find('input[name^=' + errorField + ']').closest('td');
                errorTip  = rowErrors[index][errorField];
                errorHTML = '<div id="help' + errorField + index + '" class="text-danger help-text">' + errorTip + '</div>';
                $errorTD.append(errorHTML);
                $errorTD.find('input').addClass('has-error');
            }
        }
        if(typeof rowErrors['percent'] == 'string')
        {
            errorFieldID = $(this).closest('tr').find('input[name^=percent]').attr('id');
            errorHTML    = '<div id="help' + errorFieldID + '" class="text-danger help-text">' + rowErrors['percent'] + '</div>';
            $('#' + errorFieldID).closest('td').append(errorHTML);
            $('#' + errorFieldID).closest('td').find('input').addClass('has-error');
        }

        index ++;
    });
};

window.waitDom('td[data-name=milestone]', function()
{
    $('td[data-name=milestone]').each(function()
    {
        if($(this).find('input[type=radio]:checked').length == 0) $(this).find('input[type=radio]').eq(1).prop('checked', true);
    })
})

window.changeEnabled = function(obj)
{
    const $target = $(obj);
    const tdItems = $target.closest('tr').find('td');
    const $row       = $target.closest('tr');
    const stageID    = $row.find('input[name^=id]').val();
    const stageAttr  = $row.find('input[name^=attribute]').val();
    const defaultVal = {
            'name' : stageID ? plans[stageID].name  : attributeList[stageAttr],
            'begin': stageID ? plans[stageID].begin : project.begin,
            'end'  : stageID ? plans[stageID].end   : project.end
        }

    if($target.prop('checked'))
    {
        for(let item = 0; item < tdItems.length; item++)
        {
            if($(tdItems[item]).data('name') == 'attribute') continue;

            if($(tdItems[item]).find('[data-zui-datepicker]').length)
            {
                $(tdItems[item]).find("input.hidden").last().remove();

                $(tdItems[item]).find('[data-zui-datepicker]').zui('datePicker').render({disabled: false});
            }
            else if($(tdItems[item]).find('[data-zui-picker]').length)
            {
                $(tdItems[item]).find("input.hidden").last().remove();

                $(tdItems[item]).find('[data-zui-picker]').zui('picker').render({disabled: false});
            }
            else if($(tdItems[item]).find('.radio-primary').length)
            {
                $(tdItems[item]).find("input.hidden").last().remove();

                $(tdItems[item]).find('.radio-primary').parent().removeAttr('disabled');
            }
            else if($(tdItems[item]).find('input[type=text]').length)
            {
                $(tdItems[item]).find('input[type=text]').removeAttr('readonly');
            }
            else if($(tdItems[item]).data('name') == 'enabled')
            {
                $(tdItems[item]).find("input.hidden").last().remove();
            }
        }
    }
    else
    {
        let itemValue = '';
        let itemName  = '';
        for(let item = 0; item < tdItems.length; item++)
        {
            if($(tdItems[item]).data('name') == 'attribute') continue;

            if($(tdItems[item]).find('[data-zui-datepicker]').length)
            {
                if($(tdItems[item]).find('input[name^=begin]').length) $(tdItems[item]).find('input[name^=begin]').zui('datePicker').$.setValue(defaultVal.begin);
                if($(tdItems[item]).find('input[name^=end]').length)   $(tdItems[item]).find('input[name^=end]').zui('datePicker').$.setValue(defaultVal.end);

                $(tdItems[item]).find('[data-zui-datepicker]').zui('datePicker').render();

                itemValue = $(tdItems[item]).find('[data-zui-datepicker]').zui('datePicker').$.value;
                itemName  = $(tdItems[item]).find('input.pick-value').attr('name');
                $(tdItems[item]).append("<input name='" + itemName + "' value='" + itemValue + "' class='hidden'/>");

                $(tdItems[item]).find('[data-zui-datepicker]').zui('datePicker').render({disabled: true});
            }
            else if($(tdItems[item]).find('[data-zui-picker]').length)
            {
                itemValue = $(tdItems[item]).find('input, select').zui('picker').$.value;
                itemName  = $(tdItems[item]).find('input, select').attr('name');
                $(tdItems[item]).append("<input name='" + itemName + "' value='" + itemValue + "' class='hidden'/>");

                $(tdItems[item]).find('[data-zui-picker]').zui('picker').render({disabled: true});
            }
            else if($(tdItems[item]).find('.radio-primary').length)
            {
                itemValue = $(tdItems[item]).find('input[type=radio]:checked').val();
                itemName  = $(tdItems[item]).find('input[type=radio]:checked').attr('name');
                $(tdItems[item]).append("<input name='" + itemName + "' value='" + itemValue + "' class='hidden'/>");

                $(tdItems[item]).find('.radio-primary').parent().attr('disabled', 'disabled');
            }
            else if($(tdItems[item]).find('input[type=text]').length)
            {
                 if($(tdItems[item]).find('input[name^=name]').length) $(tdItems[item]).find('input[name^=name]').val(defaultVal.name);

                $(tdItems[item]).find('input[type=text]').attr('readonly', 'readonly');
            }
            else if($(tdItems[item]).data('name') == 'enabled')
            {
                itemName = $(tdItems[item]).find('input').attr('name');
                $(tdItems[item]).append("<input name='" + itemName + "' value='off' class='hidden'/>")
            }
        }
    }
};

window.changeAttribute = function(obj)
{
    const attribute = obj.value;
    const $target   = $(obj);

    let $tr   = $target.closest('tr');
    let level = $tr.attr('data-level');
    while(true)
    {
        $nextTr = $tr.next();
        if($nextTr.length == 0) break;
        if($nextTr.attr('data-level') <= level) break;

        let $picker = $nextTr.find('.picker-box[data-name="attribute"]').zui('picker');
        $picker.render({disabled: attribute == 'mix' ? false : true});
        $picker.$.setValue(attribute);

        $tr = $nextTr;
    }
};
