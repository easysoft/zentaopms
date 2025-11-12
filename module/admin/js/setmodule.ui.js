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
                const message = openDependFeature.replace('{source}', cmLang).replace('{target}', deliverableLang);
                showDependencyConfirm(
                    message,
                    () => setModuleState('projectDeliverable', true),
                    () => setModuleState('projectCm', false)
                );
                return false;
            }
        }
        // 开启 项目变更 时，需要确保 交付物 和 基线 都已开启
        else if(name.includes('projectChange'))
        {
            const deliverableEnabled = isModuleEnabled('projectDeliverable');
            const cmEnabled          = isModuleEnabled('projectCm');

            if(!deliverableEnabled || !cmEnabled)
            {
                let message = openDependFeature.replace('{source}', changeLang);
                const missingLangs = [];

                if(!deliverableEnabled) missingLangs.push(deliverableLang);
                if(!cmEnabled) missingLangs.push(cmLang);

                message = message.replace('{target}', missingLangs.join(','));

                showDependencyConfirm(
                    message,
                    () =>
                    {
                        if(!deliverableEnabled) setModuleState('projectDeliverable', true);
                        if(!cmEnabled) setModuleState('projectCm', true);
                    },
                    () => setModuleState('projectChange', false)
                );
                return false;
            }
        }
    }
    else
    {
        // 关闭 用户需求 时，如果 业务需求 已开启，需要提示关闭 业务需求
        if(name.includes('productUR') && edition !== 'ipd')
        {
            if(isModuleEnabled('productER'))
            {
                const message = closeDependFeature.replace('{source}', URCommon).replace('{target}', ERCommon);
                showDependencyConfirm(
                    message,
                    () => setModuleState('productER', false),
                    () =>
                    {
                        setModuleState('productUR', true);
                        setModuleState('productER', true);
                    }
                );
                return false;
            }
        }
        // 关闭 交付物 时，如果 项目变更 或 基线 已开启，需要提示关闭它们
        else if(name.includes('projectDeliverable'))
        {
            const changeEnabled = isModuleEnabled('projectChange');
            const cmEnabled     = isModuleEnabled('projectCm');

            if(changeEnabled || cmEnabled)
            {
                let message = closeDependFeature.replace('{source}', changeLang);
                const activeLangs = [];

                if(changeEnabled) activeLangs.push(changeLang);
                if(cmEnabled) activeLangs.push(cmLang);

                message = message.replace('{target}', activeLangs.join(','));

                showDependencyConfirm(
                    message,
                    () =>
                    {
                        if(changeEnabled) setModuleState('projectChange', false);
                        if(cmEnabled) setModuleState('projectCm', false);
                    },
                    () => setModuleState('projectDeliverable', true)
                );
            }
        }
        // 关闭 基线 时，如果 项目变更 已开启，需要提示关闭 项目变更
        else if(name.includes('projectCm'))
        {
            if(isModuleEnabled('projectChange'))
            {
                const message = closeDependFeature.replace('{source}', cmLang).replace('{target}', changeLang);
                showDependencyConfirm(
                    message,
                    () => setModuleState('projectChange', false),
                    () => setModuleState('projectCm', true)
                );
            }
        }
    }
}