function changeModule($target)
{
    const name = $target.attr('name');
    if($target.prop('checked'))
    {
        $("input[type=hidden][name='" + name + "']").val('1').attr('disabled');
        checkRelated(name, 'open');
    }
    else
    {
        $("input[type=hidden][name='" + name + "']").val('1').attr('disabled');
        $("input[type=hidden][name='" + name + "']").val('0').removeAttr('disabled');
        checkRelated(name, 'close');
    }
};

function checkModule(event)
{
    changeModule($(event.target));
}

function checkGroup()
{
    const checked = $(this).prop('checked');
    $(this).closest('tr').find("input[type=checkbox][name^='module']").each(function()
    {
        $(this).prop('checked', checked);
        changeModule($(this));
    });
};

function checkAll()
{
    const checked = $(this).prop('checked');
    $('input[type=checkbox][name^=allChecker]').prop('checked', checked);
    $(this).closest('table').find("input[type=checkbox][name^='module']").each(function()
    {
        $(this).prop('checked', checked);
        changeModule($(this));
    });
};


window.submitForm = function()
{
    const isCheckedUR = $('[name="module[productUR]"]').prop('checked');
    const isCheckedER = $('[name="module[productER]"]').prop('checked');

    let message   = confirmDisableStoryType;
    let storyType = '';

    if(edition != 'ipd' && URAndSR && !isCheckedUR)
    {
        storyType += URCommon;
    }

    if(enableER && !isCheckedER)
    {
        if(storyType) storyType += ',';
        storyType += ERCommon;
    }

    if(storyType)
    {
        zui.Modal.confirm(
        {
            message: message.replace(/{type}/g, storyType),
            icon: 'icon-exclamation-sign',
            iconClass: 'warning-pale rounded-full icon-2x'
        }).then((res) =>
        {
            if(res)
            {
                realSubmitForm();
                return false;
            }

            setModuleState('productUR', true);
            setModuleState('productER', true);
        });
        return false;
    }

    realSubmitForm();
    return false;
}

window.realSubmitForm = function()
{
    const formData = new FormData($('#setModuleForm form')[0]);
    const url      = $.createLink('admin', 'setmodule');
    $.ajaxSubmit({url: url, data: formData});
}

/**
 * 设置模块的checkbox和hidden input状态
 * @param {string} moduleName - 模块名称（如 'productUR', 'productER'）
 * @param {boolean} checked - 是否选中
 */
function setModuleState(moduleName, checked)
{
    const checkboxSelector = `[name="module[${moduleName}]"]`;
    const hiddenSelector   = `#module${moduleName}[type=hidden]`;
    $(checkboxSelector).prop('checked', checked);
    $(hiddenSelector).val(checked ? '1' : '0');
}

/**
 * 检查模块是否已启用
 * @param {string} moduleName - 模块名称
 * @returns {boolean} 是否已启用
 */
function isModuleEnabled(moduleName)
{
    return $('[name="module[' + moduleName + ']"]').prop('checked');
}

/**
 * 显示依赖关系确认对话框
 * @param {string} message - 确认消息
 * @param {Function} onConfirm - 确认回调
 * @param {Function} onCancel - 取消回调
 */
function showDependencyConfirm(message, onConfirm, onCancel)
{
    zui.Modal.confirm(
    {
        message: message,
        icon: 'icon-exclamation-sign',
        iconClass: 'warning-pale rounded-full icon-2x'
    }).then((res) =>
    {
        if(res)
        {
            if(onConfirm) onConfirm();
            return false;
        }
        if(onCancel) onCancel();
    });
}

window.checkRelated = function(name, type)
{
    if(type === 'open')
    {
        // 开启 业务需求 时，需要确保 用户需求 已开启
        if(name.includes('productER') && edition !== 'ipd')
        {
            if(!isModuleEnabled('productUR'))
            {
                const message = openDependFeature.replace('{source}', ERCommon).replace('{target}', URCommon);
                showDependencyConfirm(
                    message,
                    () => setModuleState('productUR', true),
                    () => setModuleState('productER', false)
                );
                return false;
            }
        }
        // 开启 项目变更 时，需要确保 交付物 已开启
        else if(name.includes('projectCm'))
        {
            if(!isModuleEnabled('projectDeliverable'))
            {
                const message = openDependFeature.replace('{source}', deliverableLang).replace('{target}', changeLang);
                showDependencyConfirm(
                    message,
                    () => setModuleState('projectDeliverable', true),
                    () => setModuleState('projectCm', false)
                );
                return false;
            }
        }
}