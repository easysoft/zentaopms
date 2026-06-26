/** 异步回填场景的最大重试次数。 */
const FORM_RETRY_LIMIT = 5;
/** 异步回填场景的每次重试间隔。 */
const FORM_RETRY_DELAY = 400;

/** 获取当前页面上最合适的目标表单。 */
function getTargetForm()
{
    const selectors = [
        '#mainContent form[z-use*="BatchForm"]',
        '#mainContent form:has(.form-batch-table)',
        '#mainContent form',
        'form[z-use*="BatchForm"]',
        'form:has(.form-batch-table)',
        'form',
    ];

    for(const selector of selectors)
    {
        const $form = $(selector).first();
        if($form.length) return $form;
    }

    return $();
}

/** 获取表单对应的 ZentaoFormHelper 实例。 */
function getFormHelper($form)
{
    if(!$form || !$form.length || !window.zui || typeof window.zui.zentaoFormHelper !== 'function') return null;
    return window.zui.zentaoFormHelper($form);
}

/** 将注入数据归一为对象或数组，兼容旧版 JSON 字符串格式。 */
function normalizeInjectData(data)
{
    if(typeof data !== 'string') return data;
    if(!data.trim()) return null;

    try
    {
        return JSON.parse(data);
    }
    catch(error)
    {
        console.error('[aiFormInject] parse inject data failed:', error);
        return null;
    }
}

/** 通过统一 helper 入口执行 AI 注入。 */
function applyData(data, options)
{
    const normalizedData = normalizeInjectData(data);
    if(!normalizedData) return false;

    const $form = getTargetForm();
    if(!$form.length) return false;

    const formHelper = getFormHelper($form);
    if(!formHelper) return false;

    if(typeof formHelper.applyAIInjectData === 'function')
    {
        const result = formHelper.applyAIInjectData(normalizedData, options || {});
        return !!(result && result.success);
    }

    formHelper.setFormData(normalizedData);
    return true;
}

/** 在表单与 helper 就绪后重试执行注入。 */
function applyDataWithRetry(data, options, tries)
{
    if(applyData(data, options)) return true;
    if((tries || 0) >= FORM_RETRY_LIMIT) return false;

    setTimeout(() => applyDataWithRetry(data, options, (tries || 0) + 1), FORM_RETRY_DELAY);
    return false;
}

/** 将样式配置归一为对象。 */
function parseStyles(styles)
{
    if(!styles) return {};
    if(typeof styles === 'object') return styles;

    try
    {
        return JSON.parse(styles);
    }
    catch(error)
    {
        console.error('[aiFormInject] parse styles failed:', error);
        return {};
    }
}

/** 注入审核操作区按钮。 */
function injectAuditAction(config)
{
    if(!config) return;

    if(config.actionMode === 'doc')
    {
        const $actionContainer = $(config.actionContainer);
        if(!$actionContainer.length) return;

        $actionContainer.children('button,a').remove();
        $actionContainer.append(config.publishButton || '');
        $actionContainer.css(parseStyles(config.actionStyles));

        const $exitContainer = $(config.docExitContainer);
        if($exitContainer.length)
        {
            $exitContainer.html(config.exitButton || '');
        }
        else
        {
            $actionContainer.prepend(config.exitButton || '');
        }
        return;
    }

    const $actionContainer = $(config.actionContainer);
    if(!$actionContainer.length) return;

    const actionMethod = config.actionMethod || 'append';
    const injectHTML = `${config.publishButton || ''} ${config.exitButton || ''}`.trim();
    if(typeof $actionContainer[actionMethod] === 'function') $actionContainer[actionMethod](injectHTML);
}

/** 注入审核工具栏按钮。 */
function injectAuditToolbar(config)
{
    if(!config || !config.buttonHTML) return;

    const $toolbarContainer = $(config.toolbarContainer);
    if(!$toolbarContainer.length) return;

    const toolbarMethod = config.toolbarMethod || 'append';
    let buttonHTML = config.buttonHTML;

    if(config.toolbarClass) buttonHTML = `<div class="${config.toolbarClass}">${buttonHTML}</div>`;
    if(typeof $toolbarContainer[toolbarMethod] !== 'function') return;

    $toolbarContainer[toolbarMethod](buttonHTML);

    const styles = parseStyles(config.toolbarStyles);
    if(Object.keys(styles).length) $toolbarContainer.css(styles);
}

/** 绑定审核链路相关按钮事件。 */
function bindAuditEvents(config)
{
    $(document)
        .off('click.aiFormInjectPublish', '#promptPublish')
        .on('click.aiFormInjectPublish', '#promptPublish', function(event)
        {
            event.preventDefault();

            const promptId = $(this).data('promptid');
            if(!promptId) return;

            const aTag = document.createElement('a');
            aTag.href = createLink('ai', 'promptPublish', `promptId=${promptId}&backToTestingLocation=true`) + '#app=admin';
            aTag.style.display = 'none';
            document.body.appendChild(aTag);
            aTag.click();

            if($.appCode !== 'admin') $.apps.close($.appCode);
        });

    $(document)
        .off('click.aiFormInjectExit', '#promptAuditExit')
        .on('click.aiFormInjectExit', '#promptAuditExit', function(event)
        {
            event.preventDefault();

            const promptId = $('#promptPublish').data('promptid');
            if(!promptId) return;

            const aTag = document.createElement('a');
            aTag.href = createLink('ai', 'promptAudit', `promptId=${promptId}&objectId=0&exit=true`) + '#app=admin';
            aTag.style.display = 'none';
            document.body.appendChild(aTag);
            aTag.click();

            if($.appCode !== 'admin') $.apps.close($.appCode);
        });

    $(document)
        .off('click.aiFormInjectRegenerate', '#promptRegenerate')
        .on('click.aiFormInjectRegenerate', '#promptRegenerate', function()
        {
            $('body').attr('data-loading', config.loadingText || '');
            $('body').addClass('load-indicator loading');
        });
}

window.zentaoAIFormInject = {
    getTargetForm,

    /** 应用数字员工异步回填数据。 */
    applyPendingFormData()
    {
        if(typeof window.zentaoAIFormInjectPendingData === 'undefined') return false;
        return applyDataWithRetry(window.zentaoAIFormInjectPendingData, {source: 'pending'}, 0);
    },

    /** 应用同步 AI 注入数据。 */
    applyInjectData()
    {
        if(typeof window.injectData === 'undefined') return false;
        return applyData(window.injectData, {showMessage: true, source: 'sync'});
    },

    /** 初始化审核按钮和工具栏。 */
    initAuditControls()
    {
        const config = window.zentaoAIFormInjectAuditConfig;
        if(!config) return;

        if(config.initAction) injectAuditAction(config);

        const hasInjectData = typeof window.injectData !== 'undefined';
        const hasPendingData = typeof window.zentaoAIFormInjectPendingData !== 'undefined';
        if(config.injectToolbar && (hasInjectData || hasPendingData || config.initAction))
        {
            injectAuditToolbar(config);
        }

        bindAuditEvents(config);
    },
};
