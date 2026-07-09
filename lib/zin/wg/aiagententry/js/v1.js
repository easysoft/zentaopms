/** 获取当前页面中用于 AI 表单处理的目标表单。 */
function getPageForm()
{
    const $batchForm = $('form[z-use*="BatchForm"]').first();
    if($batchForm.length) return $batchForm;

    const $batchTableForm = $('form').has('.form-batch-table').first();
    if($batchTableForm.length) return $batchTableForm;

    return $('form').first();
}

/** 从表单字段中提取上下文 ID 映射。 */
function extractContextIDs(formSchema)
{
    if(!formSchema || !formSchema.fields) return {};

    const objectTypes = [
        'execution', 'project', 'product', 'story',
        'task', 'bug', 'case', 'testtask',
        'build', 'release', 'doc', 'requirement', 'demand'
    ];
    const contextIDs = {};

    Object.values(formSchema.fields).forEach(field =>
    {
        const value = field.currentValue;
        if(value === undefined || value === null || value === '') return;

        const numVal = parseInt(value);
        if(isNaN(numVal) || numVal <= 0) return;

        const fieldName = field.name.toLowerCase().replace(/id$/, '').replace(/_id$/, '');
        if(objectTypes.includes(fieldName)) contextIDs[fieldName] = numVal;
    });

    return contextIDs;
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
        const contextIDs = extractContextIDs(formSchema);

        let promptFields  = null;
        let allowedFields = null;
        let agentRole     = null;
        let agentPurpose  = null;
        let $targetEl = $btn;
        if($targetEl && !$targetEl.attr('data-prompt-fields'))
        {
            $targetEl = $(document).find('[data-call*="executeWithFormContext(' + promptID + ')"]').first();
        }
        if($targetEl)
        {
            const fieldsAttr = $targetEl.attr('data-prompt-fields');
            if(fieldsAttr)
            {
                try
                {
                    promptFields = JSON.parse(fieldsAttr);
                }
                catch(e) {}
            }

            const allowedAttr = $targetEl.attr('data-allowed-fields');
            if(allowedAttr)
            {
                try
                {
                    allowedFields = JSON.parse(allowedAttr);
                }
                catch(e) {}
            }

            const roleAttr = $targetEl.attr('data-agent-role');
            if(roleAttr)
            {
                try
                {
                    agentRole = JSON.parse(roleAttr);
                }
                catch(e) {}
            }

            const purposeAttr = $targetEl.attr('data-agent-purpose');
            if(purposeAttr)
            {
                try
                {
                    agentPurpose = JSON.parse(purposeAttr);
                }
                catch(e) {}
            }
        }

        if(window.parent && window.parent.executeUniversalPromptWithZentaoAPI)
        {
            const savedOpenPageForm = window.parent.openPageForm;
            const iframeGetPageForm = getPageForm;
            const iframeZUI         = zui;
            const labelToName       = {};
            if(formSchema && formSchema.fields)
            {
                Object.values(formSchema.fields).forEach(function(f)
                {
                    if(f.label && f.label !== f.name) labelToName[f.label] = f.name;
                });
            }
            window.parent.openPageForm = function(url, data, callback)
            {
                const $applyForm = iframeGetPageForm();
                if($applyForm && $applyForm.length)
                {
                    const normalizedData = data && typeof data === 'object' && !Array.isArray(data)
                        ? Object.fromEntries(
                            Object.entries(data)
                                .map(function(entry)
                                {
                                    const key       = entry[0], val = entry[1];
                                    const mappedKey = labelToName[key] || key;
                                    if(mappedKey !== key && data[mappedKey] !== undefined) return null;
                                    return [mappedKey, val];
                                })
                                .filter(Boolean)
                        )
                        : data;
                    const applyHelper = iframeZUI.zentaoFormHelper ? iframeZUI.zentaoFormHelper($applyForm) : null;
                    if(applyHelper)
                    {
                        applyHelper.setFormData(normalizedData);
                        callback && callback();
                        return;
                    }
                }
                if(savedOpenPageForm) savedOpenPageForm(url, data, callback);
            };

            window.parent.executeUniversalPromptWithZentaoAPI(formSchema, contextIDs, promptID, promptFields, allowedFields, agentRole, agentPurpose);
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
