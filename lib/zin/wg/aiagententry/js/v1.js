/** 获取当前页面中用于 AI 表单处理的目标表单。 */
function getPageForm()
{
    const $batchForm = $('form[z-use*="BatchForm"]').first();
    if($batchForm.length) return $batchForm;

    const $batchTableForm = $('form').has('.form-batch-table').first();
    if($batchTableForm.length) return $batchTableForm;

    return $('form').first();
}

/** 使用当前表单上下文执行通用智能体。 */
window.executeWithFormContext = async function(promptID)
{
    const $form = getPageForm();
    if(!$form.length) return;

    const $btn = $form.closest('.panel').find('.prompts.dropdown .btn');
    $btn && $btn.prop('disabled', true).append(' <i class="icon icon-spin icon-spinner"></i>');

    try
    {
        const formHelper = zui.zentaoFormHelper ? zui.zentaoFormHelper($form) : null;
        if(!formHelper || typeof formHelper.getFormSchema !== 'function') return;

        const formSchema = formHelper.getFormSchema() || {};
        const response = await $.ajax({
            url : $.createLink('ai', 'executeUniversalPrompt', 'promptID=' + promptID),
            type: 'POST',
            data: {
                formSchema: JSON.stringify(formSchema),
                pageUrl   : window.top.location.href,
            },
            dataType: 'json',
        });

        if(response && response.result === 'success' && response.callback && window.parent && window.parent.executeZentaoPrompt)
        {
            window.parent.openPageForm = function(url, data, callback)
            {
                const $applyForm = getPageForm();
                if($applyForm.length)
                {
                    const applyHelper = zui.zentaoFormHelper ? zui.zentaoFormHelper($applyForm) : null;
                    if(applyHelper) applyHelper.setFormData(data);
                }
                callback && callback();
            };
            window.parent.executeZentaoPrompt(response.callback.params[0], response.callback.params[1]);
        }
    }
    catch(error)
    {
        console.error('executeWithFormContext failed:', error);
    }
    finally
    {
        $btn && $btn.prop('disabled', false).find('.icon-spinner').remove();
    }
};

/** 使用当前表单上下文发起数字员工委派。 */
window.executeWithFormContextForTeammate = function(source)
{
    let $btn = null;
    if(source)
    {
        if(source.currentTarget) $btn = $(source.currentTarget).closest('[data-teammate-id]');
        if(!$btn.length && source.target) $btn = $(source.target).closest('[data-teammate-id]');
        if(!$btn.length && source.nodeType) $btn = $(source).closest('[data-teammate-id]');
    }
    if(!$btn.length && window.event)
    {
        const eventTarget = window.event.currentTarget || window.event.target;
        if(eventTarget) $btn = $(eventTarget).closest('[data-teammate-id]');
    }
    if(!$btn.length) return;

    const teammateID = $btn.attr('data-teammate-id');
    const module = $btn.attr('data-module') || (window.config ? window.config.currentModule : '');
    const method = $btn.attr('data-method') || (window.config ? window.config.currentMethod : '');
    if(!teammateID || !module || !method) return;

    const $form = getPageForm();
    if(!$form.length) return;

    const formHelper = zui.zentaoFormHelper ? zui.zentaoFormHelper($form) : null;
    if(!formHelper || typeof formHelper.getFormSchema !== 'function') return;

    const formSchema = formHelper.getFormSchema();
    if(!formSchema) return;

    sessionStorage.setItem('aiFormSchema', JSON.stringify(formSchema));
    sessionStorage.setItem('aiFormPageUrl', window.top.location.href);

    const url = $.createLink('aiteammate', 'assignagent', `teammateID=${teammateID}&objectType=${module}&objectID=0&pageInfo=${module},${method}&from=_&fromForm=1`);
    zui.Modal.open({url, size: 'sm'});

    const checkInterval = setInterval(() =>
    {
        const formSchemaInput = $('input[name="formSchema"]');
        if(!formSchemaInput.length) return;

        clearInterval(checkInterval);

        const savedFormSchema = sessionStorage.getItem('aiFormSchema');
        if(savedFormSchema) formSchemaInput.val(savedFormSchema);

        const formPageUrl = sessionStorage.getItem('aiFormPageUrl');
        if(formPageUrl)
        {
            const formPageUrlInput = $('input[name="formPageUrl"]');
            if(formPageUrlInput.length) formPageUrlInput.val(formPageUrl);
        }
    }, 100);
};
