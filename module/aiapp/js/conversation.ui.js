let lastPageID        = '';
let aiReactionsEffect = null;
let aiPanel           = zui.AIPanel.shared;
let lastParamsID      = ($.parseLink($.apps.getAppUrl()).vars || []).map(x => x[1]).join();
const takeOverFlag    = 'aiapp-conversation.takeOverToggles';
const toggleEleClass  = 'aiapp-conversation-toggle';


function openInAIPanel(chat, params)
{
    if(!aiPanel) return;
    if(typeof params === 'string') try {params = JSON.parse(params);} catch {}
    params = params || {};
    if(typeof params !== 'object') return;
    const options = {};
    if(params.postMessage)
    {
        options.postMessage = params.postMessage;
        delete params.postMessage;
    }
    if(!chat || chat === 'NEW')       options.chat = params;
    else if(typeof chat === 'string') options.chat = chat;
    aiPanel.open(options);
}

function injectEmbedStyle()
{
    const $$ = window.parent.$;
    if($$('body').hasClass('ai-embed-injected')) return;

    $$('body').addClass('ai-embed-injected');
    $$('head').append([
        '<style>',
        '.ai-panel-root[z-mode="embed"] {left: var(--zt-menu-width)!important;}',
        '.hide-menu .ai-panel-root[z-mode="embed"] {left: var(--zt-menu-fold-width)!important; display: none}',
        'body[data-app="aiapp"] .ai-panel-root[z-mode="embed"] {display: block}',
        '.ai-panel.is-embed {min-width: 1200px;  margin: calc(var(--zt-header-height) + 12px) auto 0; position: static; height: calc(100% - var(--zt-header-height) - 28px);}',
        '@media (min-width: 1400px) {.ai-panel.is-embed {padding: 0 2.5rem}}',
        '@media (min-width: 1720px) {.ai-panel.is-embed {max-width: 1720px;}}',
        '.ai-panel.is-embed .ai-chats-panel {box-shadow: 0 0 0 1px rgba(var(--color-border-rgb), 1), var(--shadow); outline: none; width: 100%!important; backdrop-filter: none; height: calc(100% - 32px)!important; position: relative; transition: none; border-radius: var(--radius)}',
        '.ai-panel.is-embed .ai-panel-header {display: none}',
        '.ai-panel-root[z-last-mode="embed"].is-mode-changing .ai-panel {opacity: 0; transition: none}',
        '</style>'
    ].join('\n'));
}

function showToggleInParent(event)
{
    event.stopPropagation();
    event.preventDefault();

    const $$      = window.parent.$;
    const $toggle = $(this);

    let id = $toggle.attr('id') || $toggle.data('takeOverToggleID');
    if(!id)
    {
        id = zui.nextGid();
        $toggle.data('takeOverToggleID', id);
    }
    const isMessageBar = id === 'messageBar';
    const toggleID     = isMessageBar ? id : `${toggleEleClass}_${id}`;
    let $trigger       = $$(toggleID);
    if(!$trigger.length)
    {
        const data = $toggle.data();
        if(data.call === 'fetchMessage' && !data.params) data.params = 'false, options.fetcher';
        $trigger = $$(`<div id="${toggleID}" class="state ${toggleEleClass}"></div>`)
            .attr('zui-toggle-dropdown', $toggle.attr('zui-toggle-dropdown'))
            .css({position: 'fixed', cursor: 'pointer'});
        Object.keys(data).forEach(key => $trigger.attr(`data-${key}`, data[key]));
        $trigger.appendTo($$('body'));
        $trigger.on('hidden', () =>
        {
            $trigger.next('.dropdown-menu').remove();
            $trigger.remove();
        });
        if(isMessageBar)
        {
            const $menu = $toggle.next('.dropdown-menu');
            const $$menu = $$('<menu></menu>').attr('class', $menu.attr('class')).attr('style', $menu.attr('style')).addClass(toggleEleClass).html($menu.html());
            $trigger.after($$menu);
        }
    }
    const boundRect = $toggle[0].getBoundingClientRect();
    $trigger.css({left: boundRect.left + $$('#menu').width(), top: boundRect.top, width: boundRect.width, height: boundRect.height});
    $trigger.trigger('click');
}

function takeOverToggles()
{
    const $toolbar = $('#toolbar > .toolbar');

    if($toolbar.data(takeOverFlag)) return;
    $toolbar.data(takeOverFlag, true);

    $toolbar.children('[zui-toggle-dropdown]').on('click.takeOverToggles', showToggleInParent);
}

window.initAIConversations = function(chat, initialParams)
{
    aiPanel = aiPanel || zui.AIPanel.shared;
    if(!aiPanel)
    {
        $('#noZaiConfigTip').removeClass('hidden');
        return;
    }

    aiPanel.toggleEmbed(true);
    $('#noZaiConfigTip').remove();

    const currentPageID = getPageInfo().id;
    lastPageID        = currentPageID;
    aiReactionsEffect = aiPanel.reactions.state$.subscribe((state) => {
        if(!state.zentaoPage || lastPageID === state.zentaoPage.id) return;
        lastPageID = state.zentaoPage.id;
        aiPanel.toggleEmbed(lastPageID === currentPageID);
    });

    if(initialParams || chat) setTimeout(() => openInAIPanel(chat, initialParams), 600);

    injectEmbedStyle();
    takeOverToggles();
}

window.onPageUnmount = function()
{
    if(aiReactionsEffect)
    {
        aiReactionsEffect();
        aiReactionsEffect = null;
    }

    if(aiPanel) aiPanel.toggleEmbed(false);

    $('#toolbar > .toolbar').data(takeOverFlag, false).children('[zui-toggle-dropdown]').off('click.takeOverToggles');
    window.parent.$('.aiapp-conversation-toggle').remove();
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
