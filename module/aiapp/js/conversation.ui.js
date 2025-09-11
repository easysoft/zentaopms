let lastPageID        = '';
let aiReactionsEffect = null;
let aiPanel           = zui.AIPanel.shared;
let lastParamsID      = ($.parseLink($.apps.getAppUrl()).vars || []).map(x => x[1]).join();

function openInAIPanel(chat, params)
{
    if(!params || !aiPanel) return;
    if(typeof params === 'string') try {params = JSON.parse(params);} catch {}
    if(!params || typeof params !== 'object') return;
    aiPanel.open({chatID: chat, postMessage: params});
}

window.initAIConversations = function(chat, initialParams)
{
    aiPanel = aiPanel || zui.AIPanel.shared;
    if(!aiPanel) return;

    aiPanel.toggleEmbed(true);

    const currentPageID = getPageInfo().id;
    lastPageID        = currentPageID;
    aiReactionsEffect = aiPanel.reactions.state$.subscribe((state) => {
        if(!state.zentaoPage || lastPageID === state.zentaoPage.id) return;
        lastPageID = state.zentaoPage.id;
        aiPanel.toggleEmbed(lastPageID === currentPageID);
    });

    if(initialParams || chat) setTimeout(() => openInAIPanel(chat, initialParams), 600);

    const parent$ = window.parent.$;
    if(parent$('body').hasClass('ai-embed-injected')) return;

    parent$('body').addClass('ai-embed-injected');
    parent$('head').append([
        '<style>',
        '.ai-panel-root[z-mode="embed"] {left: var(--zt-menu-width)!important;}',
        '.hide-menu .ai-panel-root[z-mode="embed"] {left: var(--zt-menu-fold-width)!important;}',

        '.ai-panel.is-embed {min-width: 1200px;  margin: calc(var(--zt-header-height) + 12px) auto 0; position: static; height: calc(100% - var(--zt-header-height) - 28px);}',
        '@media (min-width: 1400px) {.ai-panel.is-embed {padding: 0 2.5rem}}',
        '@media (min-width: 1720px) {.ai-panel.is-embed {max-width: 1720px;}}',
        '.ai-panel.is-embed .ai-chats-panel {box-shadow: 0 0 0 1px rgba(var(--color-border-rgb), 1), var(--shadow); outline: none; width: 100%!important; backdrop-filter: none; height: calc(100% - 32px)!important; position: relative; transition: none; border-radius: var(--radius)}',
        '.ai-panel.is-embed .ai-panel-header {display: none}',
        '</style>'
    ].join('\n'));
}

window.onPageUnmount = function()
{
    if(aiReactionsEffect)
    {
        aiReactionsEffect();
        aiReactionsEffect = null;
    }

    if(aiPanel) aiPanel.toggleEmbed(false);
};

window.beforePageLoad = function(options)
{
    if(options.id !== 'page') return;
    const link = $.parseLink(options.url);
    if(link.moduleName !== 'aiapp' || link.methodName !== 'conversation') return;

    const paramsID = link.vars.map(x => x[1]).join();
    if(paramsID === lastParamsID) return false;

    lastParamsID = paramsID;
    const params = link.vars[1][1];
    if(params) params = atob(params);
    openInAIPanel(link.vars[0][1], params);

    return false;
};
