window.checkZAIPanel = async function(showMessage)
{
    const zaiPanel = zui.AIPanel.shared;
    const store = zaiPanel ? zaiPanel.store : null;
    if(!store || !store.isConfigOK)
    {
        if(showMessage) zui.Modal.alert((store ? store.error : '') || {content: {html: zaiLang.zaiConfigNotValid}});
        return;
    }
    const isOK = await store.isOK();
    if(!isOK)
    {
        if(showMessage) zui.Modal.alert((store ? store.error : '') || {content: {html: zaiLang.unauthorizedError}});
        return;
    }
    return zaiPanel;
};

window.openPageForm = function(url, data, callback)
{
    return new Promise((resolve, reject) => {
        const openedApp = openUrl(url);
        if(!openedApp) return;
        let updateTimer = 0;
        const tryUpdateForm = () =>
        {
            if(updateTimer) clearTimeout(updateTimer);
            updateTimer = setTimeout(() =>
            {
                try
                {
                    if(data)
                    {
                        const iframe = openedApp.iframe;
                        iframe.contentWindow.applyFormData(data);
                    }
                    callback && callback(openedApp);
                    resolve(openedApp);
                } catch (error) {reject(error)}
            }, 2000);
        };
        openedApp.$app.one('updateapp.apps updatepage.app', tryUpdateForm);
        setTimeout(() => openedApp.$app.off('updateapp.apps', tryUpdateForm), 5000);
    });
}

function getPromptFormConfig(fields, extraConfig)
{
    if(!Array.isArray(fields) || !fields.length) return;
    const typeMap    = {radio: 'picker', checkbox: 'multiPicker', text: 'input'};
    const required   = [];
    const properties = fields.reduce((properties, field, index) => {
        field.code = `field-${field.id}`;
        properties[field.code] = {
            type       : 'string',
            widget     : typeMap[field.type] || field.type,
            title      : field.name,
            placeholder: field.placeholder,
            order      : index,
            required   : field.required && field.required !== '0',
            props      : zui.isNotEmptyString(field.options) ? {items: field.options.split(',').map(x => ({text: x, value: x}))}: undefined
        };
        return properties;
    }, {});
    return $.extend(
    {
        schema: {type: 'object', properties: properties, required: required},
        prompt: (data) => fields.map(x => `* ${x.name}: ${data[x.code] || ''}`).join('\n')
    }, extraConfig);
}

window.executeZentaoPrompt = async function(info, testingMode)
{
    testingMode = testingMode && testingMode !== '0';
    const zaiPanel = await checkZAIPanel(true);
    if(!zaiPanel) return;

    const htmlDiff      = await zui.HTMLDiff.loadModule();
    const langData      = zaiPanel.options.langData || {};
    const noTargetForm  = !info.targetForm || info.targetForm === 'empty.empty';
    const toolName      = `zentao_tool_${info.promptID}`;
    const dataPropNames = info.dataPropNames || {};
    const propNames     = dataPropNames[info.objectType] || {};
    const isChange      = info.schema.title === dataPropNames.common;

    if(!noTargetForm)
    {
        const properties    = info.schema.properties;
        if(propNames.title === undefined) propNames.title = info.schema.title;
        Object.keys(properties).forEach(key =>
        {
            if(propNames[key] === undefined) propNames[key] = properties[key].title || properties[key].description;
        });
    }

    const tools = noTargetForm ? [] : [{
        name       : toolName,
        displayName: info.name,
        description: info.name,
        parameters :
        {
            type: 'object',
            properties:
            {
                data:     info.schema,
                title:    {type: 'string', description: langData.promptResultTitle},
                explain:  {type: 'string', description: langData.changeExplainDesc},
            },
            required: ['data', 'explain'],
        },
        fn: (response) => {
            const result     = response.data;
            const targetForm = info.targetForm;
            if(!targetForm) return {result: result};

            const applyFormFormat = langData.applyFormFormat;
            const originObject    = info.object && info.object[info.objectType];
            const h               = zui.jsx;
            let   diffView        = null;
            const explainView     = response.explain ? h`<div><i class="icon icon-lightbulb text-gray"></i> ${response.explain}</div>` : null;
            const renderValue     = (value) =>
            {
                if(value === undefined || value === null) return '';
                if(typeof value !== 'object') return value;

                const arr = Object.keys(value) === 1 && Array.isArray(value[Object.keys(value)[0]]) ? value[Object.keys(value)[0]] : value;
                if(Array.isArray(arr))
                {
                    const firstItem = arr[0];
                    if(firstItem && (firstItem.title || firstItem.name))
                    {
                        return h`<ul>${arr.map(x => h`<li>${x.title || x.name}</li>`)}</ul>`;
                    }
                    return langData.dataListSizeInfo.replace('%s', arr.length);
                }

                return langData.notSupportPreview ;
            };
            if(isChange && originObject)
            {
                const renderProp = (prop, value) => {
                    let oldValue = originObject[prop];
                    if(oldValue === undefined || oldValue === null) oldValue = '';
                    if(value === undefined || value === null)       value    = '';
                    if(typeof oldValue === 'string' && oldValue.length) oldValue = $('<div/>').html(oldValue).text();
                    value = typeof value === 'string' ? value : JSON.stringify(value);
                    oldValue = typeof oldValue === 'string' ? oldValue : JSON.stringify(oldValue);
                    const isSame = oldValue === value;
                    return h`<tr class="whitespace-pre-wrap">
    <td class='font-bold'>${propNames[prop] || prop}</td>
    <td>${isSame ? renderValue(value) : (oldValue.length ? h`<div class="htmldiff article whitespace-prewrap" dangerouslySetInnerHTML=${{__html: htmlDiff(oldValue, value)}}></div>` : h`<div class="htmldiff article whitespace-prewrap"><ins data-operation-index="0">${value}</ins></div>`)}</td>
</tr>`;
                };
                diffView = h`<h6>${zui.formatString(langData.changeTitleFormat, {type: propNames.common || info.objectType, id: info.objectID ? `#${info.objectID}` : ''})}</h6>
<table class="table bordered" style="min-width: 600px">
    <thead>
        <tr>
            <th style="width: 100px;">${langData.changeProp}</th>
            <th>${langData.changeDetail}</th>
        </tr>
    </thead>
    <tbody>
        ${Object.entries(result).map(entry => renderProp(entry[0], entry[1]))}
    </tbody>
</table>`;
            }
            else
            {
                const renderProp = (prop, value) => {
                    return h`<div class="text-fore"><div class="font-bold">${propNames[prop] || prop}</div><div>${renderValue(value)}</div></div>`;
                };
                diffView = h`<h6>${info.targetFormName}</h6><div class="ring rounded p-2 article whitespace-prewrap col gap-2 success-pale">${Object.entries(result).map(entry => renderProp(entry[0], entry[1]))}</div>`;
            }
            localStorage.setItem('aiResult', JSON.stringify(result));

            return {
                view: [response.title ? h`<h4>${response.title}</h4>` : null, diffView, explainView],
                actions: [testingMode ? null :{
                    text        : (applyFormFormat || '%s').replace('%s', info.targetFormName || info.targetForm),
                    onClick     : () => openPageForm(info.formLocation, result, () => zui.Messager.success(langData.applyFormSuccess.replace('%s', info.targetFormName || info.targetForm))),
                    type        : 'primary-pale',
                    trailingIcon: 'icon-arrow-right'
                }, info.promptAudit ? {
                    text: langData.goTesting,
                    url:  $.createLink('ai', 'promptAudit', `promptId=${info.promptID}&objectId=${info.objectID || 0}`),
                    type: 'primary-pale',
                    'data-toggle': 'modal',
                } : null]
            };
        },
    }];
    const formConfig  = getPromptFormConfig(info.fields, info.formConfig);
    zaiPanel.openPopup({
        id         : 'zentao-prompt-popoup',
        viewType   : 'chat',
        width      : info.content ? 800 : 600,
        postMessage: formConfig ? undefined : {content: [{role: 'system', content: info.dataPrompt}]},
        creatingChat: {
            title    : info.name,
            type     : 'agent',
            model    : info.model,
            tools    : tools,
            prompt   : [info.prompt, zui.formatString(langData.promptExtraLimit, {toolName: toolName})].join('\n\n'),
            form     : formConfig,
        },
    });
};

function registerZentaoAIPlugin(lang)
{
    const plugin = zui.AIPlugin.define('zentao', {name: lang.name, icon: 'zentao'});
    plugin.defineContextProvider(
    {
        code: 'currentPage',
        title: lang.currentPage,
        icon: 'globe',
        recommend: true,
        when: () => $.apps,
        data: () => {
            const pageWindow     = $.apps.getLastApp().iframe.contentWindow;
            const page$          = pageWindow.$;
            const $mainContainer = page$('#mainContainer');
            const pageContent    = $mainContainer.length ? $mainContainer.text() : page$('body').text();
            return {
                prompt: [
                    `当前页面标题：${document.title}`,
                    "当前页面内容：",
                    pageContent
                ].join('\n\n')
            };
        },
        generate: ({userPrompt}) => {
            if(new RegExp(`@(${lang.currentPage})`, 'i').test(userPrompt)) return {};
        }
    });

    const objectIcons = {
        story   : 'file-text',
        demand  : 'file-text',
        bug     : 'bug',
        doc     : 'doc',
        design  : 'design',
        feedback: 'feedback',
    };
    const zentaoVersion = window.config?.version || '';
    const [_, zentaoEdition] = zentaoVersion.match(/^([a-zA-Z]+)?(\d+\.\d+(\.\d+)?)$/) || [];

    ['story', 'demand', 'bug', 'doc', 'design', 'feedback'].forEach(objectType => {
        if(objectType === 'feedback' && !zentaoEdition) return;
        if(objectType === 'demand' && zentaoEdition !== 'ipd') return;
        plugin.defineContextProvider({
            code: `${objectType}Lib`,
            title: lang[objectType],
            icon:  objectIcons[objectType],
            when:  ({store}) => !!store.globalMemory,
            data:
            {
                memory: {collections: ['zentao:global'], content_filter: {attrs: {objectType}}},
            },
            generate: ({userPrompt}) => {
                const objectName = lang[objectType] || objectType;
                const matches    = [...userPrompt.matchAll(new RegExp(`@(${objectName}${objectType !== objectName ? `|${objectType}` : ''})\\s?#?(\\d+)`, 'gi'))];
                if(matches.length)
                {
                    return matches.map(match => {
                        const objectID = match[2];
                        return {
                            code:      `${objectType}-${objectID}`,
                            recommend: true,
                            title:     `${objectName} #${objectID}`,
                            data: () => ({
                                memory:
                                {
                                    collections:    ['zentao:global'],
                                    content_filter: {attrs: {objectKey: `${objectType}-${objectID}`}},
                                },
                            })
                        };
                    });
                }
                if(new RegExp(`@(${objectName}${objectType !== objectName ? `|${objectType}` : ''})`, 'i').test(userPrompt)) return {};
            }
        })
    });

    plugin.defineContextProvider(
    {
        code     : 'currentDocContent',
        title    : lang.currentDocContent,
        icon     : 'doc',
        recommend: true,
        hidden   : true,
        when: () => {
            if(!window.config) return;

            const pageWindow = $.apps.getLastApp().iframe.contentWindow;
            const page$      = pageWindow.$;
            const editor     = page$("[z-use-editor]").zui();
            return !!editor;
        },
        data: async () => {
            const pageWindow = $.apps.getLastApp().iframe.contentWindow;
            const page$      = pageWindow.$;
            const editor     = page$("[z-use-editor]").zui();
            const html       = await editor.getHtml();
            const text       = $(html).text();
            return {prompt: ["当前文档内容：", text].join('\n\n')};
        },
        generate: ({userPrompt}) => {
            if (new RegExp(`@(${lang.currentDocContent})`, 'i').test(userPrompt)) return {};
        }
    });

    plugin.defineContextProvider({
        code : 'globalMemory',
        title: lang.globalMemoryTitle,
        icon : 'book',
        when : context => !!context.store.globalMemory,
        data : {memory: {collections: ['zentao:global']}},
    });

    if(lang.knowledgeLib)
    {
        plugin.defineContextProvider({
            code : 'knowledgeLibs',
            title: lang.knowledgeLib,
            icon : 'book',
            contexts : function()
            {
                return new Promise((resolve) => {
                    zui.Modal.open({url: $.createLink('ai', 'selectknowledgelib', `selectedID=&callback=getKnowledgeLibsByForm`), size: 'sm'});
                    window.getKnowledgeLibsByForm = function(libs)
                    {
                        if(!libs.length) return resolve();

                        const res = [];
                        libs.forEach(item => {
                            res.push({
                                title: item.name,
                                hint: item.name,
                                code: `zentao-knowledgeLib-${item.id}`,
                                data: {
                                    memory: {collections: [`zentao:${item.id}`]}
                                }
                            })
                        });
                        resolve(res);
                    }
                });
            },
        });
    }

    plugin.defineCallback('onCreateChat', async function(info)
    {
        if(info.isLocal || !info.userPrompt) return;

        const originMemories = info.options.memories;
        if(!originMemories || !originMemories.length) return;
        const knowledgeLibs = {};
        const otherMemories = originMemories.reduce((others, memory) =>
        {
            const ohterCollections = [];
            for(const collection of memory.collections)
            {
                if(collection.startsWith('zentao:'))
                {
                    const lib         = collection.substr(7);
                    const newFilter   = $.extend(true, {}, memory.content_filter);
                    if(!Object.keys(newFilter).length)
                    {
                        knowledgeLibs[lib] = {};
                        break;;
                    }

                    const oldFilter   = knowledgeLibs[lib] ? knowledgeLibs[lib] : null;
                    const finalFilter = $.extend(true, {}, oldFilter, newFilter);
                    if(newFilter && newFilter.attrs && oldFilter && oldFilter.attrs)
                    {
                        Object.keys(oldFilter.attrs).forEach(attrName =>
                        {
                            const oldAttr = oldFilter.attrs[attrName];
                            const newAttr = newFilter.attrs[attrName];
                            if(oldAttr === undefined || newAttr === undefined) return;
                            const finalAttr = typeof oldAttr === 'object' ? oldAttr : {$in: [oldAttr]};
                            if(typeof newAttr === 'object') finalAttr.$in = [...finalAttr.$in, ...newAttr.$in];
                            else finalAttr.$in = [...finalAttr.$in, newAttr];
                            finalFilter.attrs[attrName] = finalAttr;
                        });
                    }
                    knowledgeLibs[lib] = finalFilter;
                    continue;
                }
                ohterCollections.push(collection);
            }
            if(ohterCollections.length) others.push($.extend({}, memory, {collections: ohterCollections}));
            return others;
        }, []);

        if(!Object.keys(knowledgeLibs).length) return;

        return {memories: otherMemories, customData: {ztklibs: knowledgeLibs}};
    });

    plugin.defineCallback('onPostMessage', async function(info)
    {
        if(!info.userMessages || !info.userMessages.length) return;
        if(!info.chat.custom_data || !info.chat.custom_data.ztklibs) return;
        const userPrompt = info.userMessages.map(x => x.content).filter(x => x && x.trim().length).join('\n\n');
        if(!userPrompt.length) return;

        info.updateState(lang.searchingKLibs);

        const ztklibs  = info.chat.custom_data.ztklibs;
        const ztChunks = info.chat.$local.ztChunks || {};
        const [response] = await $.ajaxSubmit(
        {
            url:  $.createLink('zai', 'ajaxSearchKnowledges'),
            data: {userPrompt: userPrompt, filters: JSON.stringify(ztklibs)}
        });
        if(response && response.result === 'success' && response.data && Array.isArray(response.data) && response.data.length)
        {
            const newPropms = [];
            const newRefs   = [];
            const refKeys   = new Set();
            response.data.forEach(item =>
            {
                if(ztChunks[item.id]) return;
                ztChunks[item.id] = 1;
                newPropms.push(item.content);
                if(refKeys.has(item.key)) return;
                const itemAttrs = item.attrs || {};
                newRefs.push({key: item.key, name: itemAttrs.objectTitle || item.knowledgeTitle, type: itemAttrs.objectType || 'knowledge', id: itemAttrs.objectID || item.knowledgeID})
                refKeys.add(item.key);
            });
            info.chat.$local.ztChunks = ztChunks;
            return {systemPrompt: newPropms.filter(Boolean).join('\n\n'), refs: newRefs};
        }
    });
}

/* Bind AI commands in app when app is loaded, example:
$(document).on('loadapp.apps updateapp.apps', (e, args) =>
{
    const win = (e.type === 'updateapp' ? $.apps.openedApps[args[0]] : args).iframe.contentWindow;
    bindAICommandsInApp(win, 1000);
});*/
function bindAICommandsInApp(win, delay)
{
    if(!win || !win.zui || win._bindedAICommands !== undefined) return;
    const panel = win.zui.AIPanel.shared;
    if(!panel) return;
    if(win._bindedAICommands) clearTimeout(win._bindedAICommands);
    win._bindedAICommands = setTimeout(() =>
    {
        win.zui.bindCommands(win.document.body,
        {
            commands: {},
            scope: panel.commandScope,
            onCommand: panel.executeCommand.bind(panel)
        });
        win._bindedAICommands = 0;
    }, delay || 0);
}

$(() =>
{
    if(getZentaoPageType() !== 'home') return bindAICommandsInApp(window);

    const zentaoConfig = window.config
    if(!zentaoConfig || zentaoConfig.currentModule !== 'index' || zentaoConfig.currentMethod !== 'index') return;

    const zaiConfig = window.zai || window.top.zai;
    if(zaiConfig)
    {
        registerZentaoAIPlugin(zaiLang);

        const aiStore = zui.ZAIStore.createFromZentao(zaiConfig);
        if(!aiStore) return

        let userAvatarProps;
        zui.AIPanel.init(
        {
            store            : aiStore,
            position         : {bottom: +window.config.debug > 4 ? 56 : 40, right: 16},
            maximizedPosition: {left: 'calc(var(--zt-menu-width) + 4px)', top: 4, bottom: 'calc(var(--zt-apps-bar-height) + 4px)', right: 16},
            langData         : zaiLang,
            getAvatar        : (info, props) =>
            {
                if(info.role === 'user')
                {
                    if(userAvatarProps) return userAvatarProps;
                    const $avatar = $.apps.getLastApp().iframe?.contentWindow.$('#userMenu-toggle>.avatar');
                    if($avatar?.length)
                    {
                        userAvatarProps =
                        {
                            text      : $avatar.find('.avatar-text').text(),
                            code      : window.config.account,
                            src       : $avatar.find('img').attr('src'),
                            icon      : undefined,
                            background: $avatar.css('backgroundColor'),
                            foreColor : $avatar.css('color'),
                        };
                        return userAvatarProps;
                    }
                }
                return props;
            },
            getErrorContent: (error) =>
            {
                let html = '';
                if(error.type === 'unauthorized' && zaiLang.unauthorizedError) html = zui.formatString(zaiLang.unauthorizedError, {zaiConfigUrl: $.createLink('zai', 'setting')})
                else if(error.type === 'configNotValid' && zaiLang.zaiConfigNotValid) html = zui.formatString(zaiLang.zaiConfigNotValid, {zaiConfigUrl: $.createLink('zai', 'setting')})

                if(html.length) return {html: `<div class="row gap-3"><i class="mt-1 icon icon-exclamation text-warning"></i><div class="text-left pr-8">${html}</div></div>`};
                return error.message;
            },
        });

        $(document).on('updatepage.app openapp.apps openOldPage.apps', (e, args) =>
        {
                const panel = zui.AIPanel.shared;
                if(!panel) return;

                const pageInfo = e.type === 'openapp' ? args[0]?.getPageInfo?.() : args[0];
                if(!pageInfo || !pageInfo.id) return

                panel.reactions.trigger(
                    e.type === 'openapp' ? 'openPage' : 'updatepage',
                    {page: pageInfo},
                    {zentaoPage: pageInfo, event: e}
                );

                const lastPageID = panel.reactions.state.lastPageID;
                if(lastPageID !== pageInfo.id)
                {
                    panel.reactions.trigger(
                        'openNewPage',
                        {page: pageInfo},
                        {zentaoLastPageID: pageInfo.id, event: e},
                        {lifeTime: 5000}
                    );
                }
            }
        );

        aiStore.isOK().then(isOK => {window.isZaiOK = isOK;});
    }
});
