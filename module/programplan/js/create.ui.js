window.onRenderRow = function(row, rowIdx, data)
{
    if(row.children('[data-name=milestone]').find('input[type=radio]:checked').length == 0) row.children('[data-name=milestone]').find('input[type=radio]').eq(1).prop('checked', true);
    row.children('[data-name=type]').find('[name^=type]').picker({disabled: true});
    if(typeof data != 'undefined' && typeof data.id != 'undefined') row.find('[data-type="delete"]').css('display', 'none');

    if(project.model == 'ipd' && planID == '0')
    {
        const $attribute = data.attribute;
        const $point     = row.find('[data-name="point"]');

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

        $('thead [data-name="ACTIONS"]').css('display', 'none');
        row.find('[data-name="ACTIONS"]').css('display', 'none');
        row.find('[data-name="attribute"]').find('.picker-box').on('inited', function(e, info)
        {
            let $attributePicker = info[0];
            $attributePicker.render({disabled: true});
        });

        if(data.hasOwnProperty('status') && data.status != 'wait')
        {
            row.find('[data-name=enabled] input').attr('disabled', 'disabled');
            row.find('[data-name=enabled]').attr('title', cropStageTip);
        }

        if(data.enabled == 'off')
        {
            /* 需要等该行所有input元素加载完再执行changeEnabled方法 */
            window.waitDom("tr[data-index='" + rowIdx + "'] [data-name='PM'] input", function(){changeEnabled(row.find('[data-name=enabled] [name^=enabled]'));});
        }

        window.waitDom("div.picker-multi-selection", function()
        {
            let selection = row.find('div.picker-multi-selection');
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

    if(data != undefined)
    {
        if(data.hasOwnProperty('type'))
        {
            row.find('[data-name="type"]').find('.picker-box').on('inited', function(e, info)
            {
                let $type = info[0];
                $type.render({disabled: true});
            });
        }
    }

    if(data != undefined && data.disabled)
    {
        let name  = '';
        let value = '';
        const checkBoxList = row.children('[data-name=milestone]').find('input');
        Object.values(checkBoxList).forEach(function(ele){
            if(typeof ele !== 'object') return;

            name = ele.name;
            if(ele.checked)
            {
                value = ele.value;
            }
        });

        checkBoxList.attr('disabled', 'disabled');

        /* Append hidden input element for passing value. */
        let inputEle = document.createElement('input');
        inputEle.setAttribute('name',  name);
        inputEle.setAttribute('value', value);
        inputEle.setAttribute('type',  'hidden');
        row.children('[data-name=milestone]').append(inputEle);
    }
    if(!data || !data.planIDList) return;

    row.children('.form-batch-row-actions').children('[data-type=delete]').addClass('hidden');
    row.children('[data-name=type]').children('select').attr('disabled', 'disabled');
};

window.onChangeExecutionType = function(event)
{
    loadPage($.createLink('programplan', 'create', `projectID=${projectID}&productID=${productID}&planID=${planID}&type=` + $(event.target).val()));
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
}
