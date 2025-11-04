window.handleClickBatchFormAction = function(action, $row, rowIndex)
{
    if(action == 'delete')
    {
        const $nextRow = this.$tbody.find('tr[data-index="' + rowIndex + '"]');
        if($nextRow.length == 0) return;
        if($nextRow.find('td[data-name=type]').hasClass('hidden')) return;

        const $prevRow = $nextRow.prev();
        if($prevRow.length == 1 && $prevRow.attr('data-level') != $nextRow.attr('data-level') - 1) return;

        let $typePicker = $nextRow.find('.picker-box[data-name=type]').zui('picker');
        let typeItems   = [];
        for(i in typeList) typeItems.push({'text': typeList[i], 'value': i});
        $typePicker.render({items: typeItems});
    }

    if(action !== 'addSub' && action !== 'addSibling') return;

    $this = this;
    if(action == 'addSub' && $row.find('td[data-name="id"] input[name^=id]').val() > 0)
    {
        const $syncData = $row.find('td[data-name="syncData"] input[name^=syncData]');
        if($syncData.val() == '0')
        {
            const executionID = $row.find('td[data-name="id"] input[name^=id]').val();
            $.get($.createLink('project', 'ajaxCheckHasStageData', `executionID=${executionID}`), function(hasData)
            {
                if(!hasData) return window.clickToAddRow($this, action, $row, rowIndex);

                zui.Modal.confirm(confirmCreateTip).then((res) =>
                {
                    if(!res) return;

                    $syncData.val('1');
                    window.clickToAddRow($this, action, $row, rowIndex);
                });
            });
        }
        else
        {
            window.clickToAddRow($this, action, $row, rowIndex);
        }
    }
    else
    {
        window.clickToAddRow($this, action, $row, rowIndex);
    }
};

window.clickToAddRow = function($this, action, $row, rowIndex)
{
    if(!$this.nestedLevelMap) $this.nestedLevelMap = {};
    const level   = $this.nestedLevelMap[$row.attr('data-gid')] || 0;
    const nextGid = $this._idSeed++;
    $this.nestedLevelMap[nextGid] = action === 'addSub' ? level + 1 : level;
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
    $this.addRow(rowIndex, nextGid);
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
    $row.find(nestedTextSelector).attr('title', text).text(text).append(`<input type="hidden" data-name="level" name="level[${index + 1}]" value="${level}">`); // 创建隐藏表单域用于向服务器提交当前行层级信息。

    /* 追加 parent 属性，以记录父级index。 */
    let $prevLevelRow = $prevRow;
    $row.attr('data-parent', '-1');
    if($prevRow.length == 1)
    {
        if($prevLevelRow.attr('data-level') != level - 1) $prevLevelRow = $row.prevAll('tr[data-level="' + (level - 1) + '"]').first();
        if($prevLevelRow.length == 1) $row.attr('data-parent', $prevLevelRow.attr('data-gid'));
    }

    if($row.find('input[data-name="milestone"]:checked').length == 0) $row.find('input[data-name="milestone"]').eq(1).prop('checked', true); //里程碑默认选择"否"。
    if($prevLevelRow.length && $prevLevelRow.find('input[data-name="syncData"]').val() == '1') $row.find('input[data-name="syncData"]').val(1);

    /* 处理已有数据字段状态。隐藏的删除按钮，禁用管理方法字段。 */
    if(typeof data != 'undefined' && typeof data.id != 'undefined')
    {
        $row.find('[data-name="ACTIONS"]').find('[data-type="delete"]').addClass('hidden');
        $row.find('[data-name="type"]').find('.picker-box').on('inited', function(e, info){ info[0].render({disabled: true}); });
    }

    /* 如果管理方法不是"阶段"，禁用拆分子级按钮，禁用工作量占比字段。 */
    const $currentType = $row.find('[data-name="type"] input[name^=type]');
    if((data != undefined && data.type != undefined && data.type != 'stage') || ($currentType.length && $currentType.val() != 'stage'))
    {
        $row.find('input[data-name="percent"]').prop('disabled', true);
        $row.find('[data-name="ACTIONS"]').find('[data-type="addSub"]').prop('disabled', true).attr('title', addSubTip);
    }

    $row.find('[data-name="type"]').find('.picker-box').on('inited', function(e, info)
    {
        let $typePicker = info[0];

        let dataExist = (typeof data != 'undefined' && typeof data.type != 'undefined');
        let options   = {};
        options.disabled = (planID == 0 && level == 0) ? true : false;
        if(dataExist) options.disabled = true;

        let prevType = '';
        if(level > 0)
        {
            let $firstChild = $prevLevelRow.next();
            if($firstChild.length > 0)
            {
                let $firstType = $firstChild.find('[data-name="type"]').find('[name^=type]');
                if($firstType.length > 0) prevType = $firstType.val();
            }
            if(prevType && $firstChild.attr('data-index') != $row.attr('data-index'))
            {
                options.items = [];
                for(i in $typePicker.options.items)
                {
                    let item = $typePicker.options.items[i];
                    if(item.value == '') continue;
                    if(prevType == 'stage' && item.value == 'stage') options.items.push(item);
                    if(prevType != 'stage' && item.value != 'stage') options.items.push(item);
                }
            }
        }
        else if(level == 0 && planID && $row.attr('data-index') > 0)
        {
            options.items = [];
            for(i in $typePicker.options.items)
            {
                let item = $typePicker.options.items[i];
                if(item.value == '') continue;
                if(initType == 'stage' && item.value == 'stage') options.items.push(item);
                if(initType != 'stage' && item.value != 'stage') options.items.push(item);
            }
        }

        $typePicker.render(options);
        if(!dataExist)
        {
            if(level > 0  && prevType != '' && prevType != 'stage') $typePicker.$.setValue('sprint');
            if(level == 0 && planID) $typePicker.$.setValue(initType);
        }
    });

    /* Render type picker when move. */
    let $typePicker = $row.find('[data-name="type"]').find('.picker-box').zui('picker');
    if($typePicker != undefined && level > 0)
    {
        let $prevRow  = $row.prev();
        let typeItems = [];
        if($prevRow.length == 0 || $prevRow.attr('data-level') < level)
        {
            for(i in typeList) typeItems.push({'text': typeList[i], 'value': i});
        }
        else
        {
            let type = $row.find('[data-name="type"]').find('[name^=type]').val();
            for(i in typeList)
            {
                if(type == 'stage' && i == 'stage') typeItems.push({'text': typeList[i], 'value': i});
                if(type != 'stage' && i != 'stage') typeItems.push({'text': typeList[i], 'value': i});
            }
        }

        $typePicker.render({items: typeItems});
    }

    /* 已关闭且有交付物的阶段无法变更阶段类型。 */
    $row.find('[data-name="attribute"]').find('.picker-box').on('inited', function(e, info)
    {
        if(typeof data != 'undefined' && typeof data.hasDeliverable != 'undefined') info[0].render({disabled: true});
    });

    if(project.model == 'ipd')
    {
        if(planID == 0 && level == 0)
        {
            $row.find('[data-name="ACTIONS"]').find('[data-type="sort"]').addClass('disabled').attr('title', sortableTip);
            $row.find('[data-name="ACTIONS"]').find('[data-type="addSibling"]').prop('disabled', true).attr('title', addSiblingTip);
            $row.find('[data-name="ACTIONS"]').find('[data-type="delete"]').addClass('hidden');
        }

        $row.find('[data-name="attribute"]').find('.picker-box').on('inited', function(e, info){ info[0].render({disabled: true}); });

        const $point = $row.find('[data-name="point"],[data-name="defaultPoint"]');
        $point.find('.picker-box').on('inited', function(e, info)
        {
            let items    = [{text: '', value: ''}];
            let disabled = level > 0 ? true : false;

            if(typeof data != 'undefined' && typeof data.attribute != 'undefined' && !disabled)
            {
                const stageID = data.stageID;
                for(let point in ipdStagePoint[stageID])
                {
                    let disabled = false
                    let text     = ipdStagePoint[stageID][point];
                    let hint     = text;

                    /* 如果已经评审过，则置灰不能选。*/
                    if(reviewedPoints[point] !== undefined && reviewedPoints[point].disabled)
                    {
                        disabled = true;
                        hint     = reviewedPointTip;
                    }

                    items.push({text: text, value: point, disabled: disabled, hint: hint});
                }
            }

            info[0].render({items: items, disabled: disabled});
        });

        const $enabled = $row.find('td[data-name=enabled]');
        if($enabled.length > 0 && !$enabled.hasClass('hidden'))
        {
            const $checkbox = $enabled.find('input[type=checkbox]');
            if(data != undefined && data.status != undefined && data.status != 'wait') $checkbox.attr('disabled', 'disabled').attr('title', cropStageTip);
            if(level > 0) $checkbox.attr('disabled', 'disabled').attr('title', childEnabledTip);

            if($enabled.find('input.hidden').length == 0) $enabled.append("<input type='hidden' name='" + $checkbox.attr('name') + "' value='on' class='hidden'/>")
            if($enabled.find('input.hidden').length > 0)  $enabled.find('input.hidden').attr('name', $checkbox.attr('name'));

            let $rootRow = $row;
            if($rootRow.attr('data-level') != 0) $rootRow = $row.prevAll('tr[data-level="0"]').first();
            if($rootRow.length == 1 && !$rootRow.find('td[data-name=enabled] input[type=checkbox]').prop('checked'))
            {
                $row.addClass('disabled');
                $checkbox.prop('checked', false);
                $enabled.find('input.hidden').val('off');
            }
        }

        window.waitDom("div.picker-multi-selection", function()
        {
            let selection = $row.find('div.picker-multi-selection');
            selection.each(function(index, element) {
                /* 获取评审点ID。*/
                let pointID = $(element).find('div.picker-deselect-btn').data('value');

                /* 如果该评审点以提交评审，则不能取消选择。*/
                if(reviewedPoints[pointID] !== undefined && reviewedPoints[pointID].disabled)
                {
                    $(element).attr('title', reviewedPointTip); // 修改title
                    $(element).find(".picker-deselect-btn").remove(); // 禁用点击事件
                }
            });
        });
    }

    if(level > 0 || planGrade > 1)
    {
        let preAttribute = $prevLevelRow.length == 0 ? '' : $prevLevelRow.find('[name^=attribute]').val();
        $row.find('[data-name="attribute"]').find('.picker-box').on('inited', function(e, info)
        {
            let $attributePicker = info[0];

            let disabled = false;
            if(preAttribute && preAttribute != 'mix') disabled = true;
            if(data != undefined && data.attribute != undefined && data.attribute != 'mix') disabled = true;

            if(disabled)
            {
                $attributePicker.render({disabled: disabled});
                if(preAttribute) $attributePicker.$.setValue(preAttribute);
            }
        });
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

window.changeEnabled = function(obj)
{
    const $target = $(obj);
    const $row    = $target.closest('tr');
    $row.toggleClass('disabled', !$target.prop('checked'))

    let $checkbox = $row.find('[data-name=enabled]').find('input[type=checkbox]');
    if($row.find('[data-name=enabled]').find('input.hidden') == 0) $row.find('[data-name=enabled]').append("<input type='hidden' name='" + $checkbox.attr('name') + "' value='on' class='hidden'/>");
    $row.find('[data-name=enabled]').find('input.hidden').val($target.prop('checked') ? 'on' : 'off');

    let $nextRow = $row.next();
    while(true)
    {
        if($nextRow.length == 0) break;
        if($nextRow.attr('data-level') == 0) break;

        $nextRow.toggleClass('disabled', !$target.prop('checked'))

        let $nextCheckbox = $nextRow.find('[data-name=enabled]').find('input[type=checkbox]');
        if($nextRow.find('[data-name=enabled]').find('input.hidden') == 0) $nextRow.find('[data-name=enabled]').append("<input type='hidden' name='" + $nextCheckbox.attr('name') + "' value='on' class='hidden'/>");

        $nextCheckbox.prop('checked', $target.prop('checked'));
        $nextRow.find('[data-name=enabled]').find('input.hidden').val($target.prop('checked') ? 'on' : 'off');

        $nextRow = $nextRow.next();
    }
};

window.changeAttribute = function(obj)
{
    const attribute = obj.value;
    const $target   = $(obj);

    let $row     = $target.closest('tr');
    let level    = $row.attr('data-level');
    let $nextRow = $row.next();
    while(true)
    {
        if($nextRow.length == 0) break;
        if($nextRow.attr('data-level') <= level) break;

        let $picker = $nextRow.find('.picker-box[data-name="attribute"]').zui('picker');
        $picker.render({disabled: attribute == 'mix' ? false : true});
        $picker.$.setValue(attribute);

        $nextRow = $nextRow.next();
    }
};

window.changeType = function(obj)
{
    const type    = obj.value;
    const $target = $(obj);
    let $row      = $target.closest('tr');
    $row.find('input[data-name="percent"]').prop('disabled', type != 'stage');
    $row.find('[data-name="ACTIONS"]').find('[data-type="addSub"]').prop('disabled', type != 'stage').attr('title', type != 'stage' ? addSubTip : '');

    let $nextRow    = $row.next();
    let level       = $row.attr('data-level');
    let $typePicker = $row.find('.picker-box[data-name=type]').zui('picker');

    /* 检查子阶段是否又被拆分，如果有拆分，则不允许修改为非阶段的管理方法。 */
    if(type != 'stage')
    {
        while(true)
        {
            if($nextRow.length == 0) break;
            if($nextRow.attr('data-level') < level) break;
            if($nextRow.attr('data-level') > level)
            {
                zui.Modal.alert(errorLang.notStage);
                $typePicker.$.setValue('stage');
                return;
            }

            $nextRow = $nextRow.next();
        }
    }

    $nextRow = $row.next();
    if($nextRow.length == 0) return;
    if($nextRow.attr('data-level') != level) return;  //只修改同级的管理方法。

    $nextRow.find('input[data-name="percent"]').prop('disabled', type != 'stage');
    $nextRow.find('[data-name="ACTIONS"]').find('[data-type="addSub"]').prop('disabled', type != 'stage').attr('title', type != 'stage' ? addSubTip : '');

    let $nextTypePicker = $nextRow.find('.picker-box[data-name=type]').zui('picker');
    if($nextTypePicker == undefined) return;

    let nextTypeItems = [];
    for(i in typeList)
    {
        if(i == '') continue;
        if(type == 'stage' && i == 'stage') nextTypeItems.push({'text': typeList[i], 'value': i});
        if(type != 'stage' && i != 'stage') nextTypeItems.push({'text': typeList[i], 'value': i});
    }
    $nextTypePicker.render({items: nextTypeItems});
    $nextTypePicker.$.setValue(type);
};

window.onMove = function(event, originEvent)
{
    const fromLevel  = $(event.dragged).attr('data-level');
    const toLevel    = $(event.related).attr('data-level');
    const fromParent = $(event.dragged).attr('data-parent');
    const toParent   = $(event.related).attr('data-parent');

    if(fromLevel != toLevel)   return false;
    if(fromParent != toParent) return false;

    return true;
}

window.onSort = function(e)
{
    const gid         = $(e.item).attr('data-gid');
    const $duplicates = $(`tr[data-gid='${gid}']`);

    if($duplicates.length > 1) $duplicates.slice(1).remove();

    const id = $(e.item).attr('data-parent');
    window.moveChildren(id);

    const $batchForm = $('[data-zui-batchform]').zui('batchForm');
    $batchForm._rows = $batchForm._sortable.toArray().map(Number);
    $batchForm.render();
}

window.moveChildren = function(id, processedIds = new Set())
{
    if(processedIds.has(id)) return;
    processedIds.add(id);

    const $parent   = $(`tr[data-gid='${id}']`);
    const $children = $(`tr[data-parent='${id}']`).not('.sortable-empty-shadow');

    if($children.length == 0) return;
    const $reversedChildren = $($children.get().reverse());

    $reversedChildren.each(function(index, element)
    {
        $parent.after(element);
        moveChildren($(element).attr('data-gid'), processedIds);
    });
}
