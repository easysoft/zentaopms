window.checkZAIPanel = async function(showMessage)
{
    const zaiPanel = zui.AIPanel.shared;
    const store = zaiPanel ? zaiPanel.store : null;
    if(!store || !store.isConfigOK)
    {
        if(showMessage) zui.Modal.alert((store ? store.error : '') || {content: {html: zaiLang.zaiConfigNotValid}});
        return;
    }
    await store.waitInited();
    if(!store.ok)
    {
        if(showMessage) zui.Modal.alert((store ? store.error : '') || {content: {html: zaiLang.unauthorizedError}});
        return;
    }
    return zaiPanel;
};

window.openPageForm = function(url, data, callback)
{
    if(data && typeof data === 'object' && !Array.isArray(data))
    {
        const keys = Object.keys(data);
        if(keys.length === 1 && Array.isArray(data[keys[0]])) data = data[keys[0]];
    }

    return new Promise((resolve, reject) => {
        localStorage.setItem('aiResult', JSON.stringify(data));
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
    const properties = fields.reduce((properties, field, index) =>
    {
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
        schema: {type: 'object', properties: properties},
        prompt: (data) => fields.map(x => `* ${x.name}: ${data[x.code] || ''}`).join('\n')
    }, extraConfig);
}

window.executeZentaoPrompt = async function(info, testingMode)
{
    testingMode = testingMode && testingMode !== '0';
    const zaiPanel = await checkZAIPanel(true);
    if(!zaiPanel) return;

    const langData      = zaiPanel.options.langData || {};
    const noTargetForm  = !info.targetForm || info.targetForm === 'empty.empty';
    const toolName      = `zentao_tool_${info.promptID}`;
    const agentTool     = noTargetForm ? null : {
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
                summary:  {type: 'string', description: langData.agentResultSummary},
            },
            required: ['data', 'summary'],
        },
    };
    const agentSchemaProps = info.schema && info.schema.properties ? info.schema.properties : null;
    const agentLabelMap = {};
    if(agentSchemaProps)
    {
        Object.keys(agentSchemaProps).forEach(function(key)
        {
            const desc = agentSchemaProps[key].title || agentSchemaProps[key].description;
            if(desc && desc !== key) agentLabelMap[desc] = key;
        });
    }
    const tools = noTargetForm ? [] : [{
        ...agentTool,
        fn: (response) => {
            const rawData = response.data;
            const result = rawData && typeof rawData === 'object' && !Array.isArray(rawData)
                ? Object.fromEntries(
                    Object.entries(rawData)
                        .map(function(entry)
                        {
                            const key       = entry[0], val = entry[1];
                            const mappedKey = agentLabelMap[key] || key;
                            if(mappedKey !== key && rawData[mappedKey] !== undefined) return null;
                            return [mappedKey, val];
                        })
                        .filter(Boolean)
                )
                : rawData;
            const targetForm = info.targetForm;
            if(!targetForm) return {result: result};

            let normalizedProps = info.dataPropNames;
            const objType = info.objectType;
            if(result && typeof result === 'object' && !Array.isArray(result) && normalizedProps)
            {
                const engNames = {};
                Object.keys(result).forEach(function(k) { engNames[k] = k; });
                const typeProps = normalizedProps[objType] || normalizedProps;
                if(typeof typeProps === 'object')
                {
                    Object.keys(typeProps).forEach(function(k)
                    {
                        if(engNames[k] === undefined) engNames[k] = typeProps[k];
                    });
                }
                if(normalizedProps[objType])
                {
                    normalizedProps = {};
                    normalizedProps[objType] = engNames;
                }
                else normalizedProps = engNames;
            }
            const taskResult =
            {
                agentID       : info.promptID,
                id            : `zentao-agent-result-${info.promptID}`,
                tool          : agentTool,
                title         : response.title,
                result        : response,
                formLocation  : info.formLocation,
                targetFormName: info.targetFormName,
                targetForm    : info.targetForm,
                objectID      : info.objectID,
                objectType    : info.objectType,
                objectData    : info.objectData || info.object,
                objectProps   : normalizedProps,
                actions: info.promptAudit ? [{
                    text         : langData.goTesting,
                    url          : $.createLink('ai', 'promptAudit', `promptId=${info.promptID}&objectId=${info.objectID || 0}`),
                    type         : 'primary-pale',
                    'data-toggle': 'modal',
                }] : [],
            };
            const message =
            {
                role: 'user',
                content: [info.name + zui.formatString(langData.processedDataResult, {data: JSON.stringify(result)}), zui.formatString(langData.promptResultReturn, {formName: info.targetFormName})].join('\n\n'),
                custom_data: {taskResults: [taskResult], asRole: 'assistant'}
            };
            return {message: message};
        },
    }];
    const klibs        = (info.knowledgeLib ? info.knowledgeLib.split(',') : []).filter(Boolean).map(x => `zentao:${x}`);
    const formConfig   = getPromptFormConfig(info.fields, info.formConfig);
    const postMessage  = {content: [{role: 'user', content: info.purpose, custom_data: {invisible: true}}]};
    const popupOptions = {
        id         : 'zentao-prompt-popoup',
        viewType   : 'chat',
        width      : info.content ? 800 : 600,
        postMessage,
        creatingChat: {
            title    : info.name,
            type     : 'agent',
            model    : info.model,
            tools    : tools,
            prompt   : [info.role, zui.formatString(langData.processDataPrefix, {data: info.dataPrompt}), noTargetForm ? null : zui.formatString(langData.promptExtraLimit, {toolName: toolName})].filter(Boolean).join('\n\n'),
            form     : formConfig,
            memories : klibs.length ? [{collections: klibs}] : undefined,
            skills   : Array.isArray(info.skills) && info.skills.length ? info.skills : undefined,
        },
    };
    const popup = zaiPanel.openPopup(popupOptions);
    await new Promise(resolve => requestAnimationFrame(resolve));
    return popup;
};

/**
 * 执行通用表单智能体（同步流程）。
 * 收集当前表单结构和值，构建 schema 和 prompt，打开 AI Panel 供 LLM 填充。
 * 支持单个表单（type: object）和批量表单（type: array）。
 *
 * @param {Object}  formSchema     - 表单结构和当前值
 * @param {Object}  contextIDs     - 上下文字段 ID 映射
 * @param {number}  promptID       - 智能体 ID
 * @param {Array}   promptFields   - 自定义输入字段列表
 * @param {Array}   allowedFields  - 可操作字段白名单
 * @param {string}  agentRole      - 智能体角色描述
 * @param {string}  agentPurpose   - 智能体目的描述
 * @param {boolean} isBatch        - 是否为批量表单
 * @param {Array}   skills         - ZAI skillID UUID 列表
 */
window.executeUniversalPromptWithZentaoAPI = async function(formSchema, contextIDs, promptID, promptFields, allowedFields, agentRole, agentPurpose, isBatch, skills)
{
    const zaiPanel = await checkZAIPanel(true);
    if(!zaiPanel) return;

    const langData = zaiPanel.options.langData || {};
    const rawFields = (formSchema && formSchema.fields) ? formSchema.fields : {};
    const hasWhitelist = Array.isArray(allowedFields) && allowedFields.length > 0;
    const skipFields = new Set(['uid', 'token', 'referrer', 'fileList', 'contactList', 'color']);

    const fields = Object.values(rawFields).filter(f =>
    {
        if(!f.name || skipFields.has(f.name)) return false;
        if(typeof f.currentValue === 'string' && f.currentValue.startsWith('[')) return false;
        if(hasWhitelist && !allowedFields.includes(f.name)) return false;
        return true;
    });

    const properties = {};
    const required = [];
    const labelToName = {};
    const seenNames = new Set();
    fields.forEach(field =>
    {
        const name = field.name;
        if(seenNames.has(name)) return;
        seenNames.add(name);
        const isStepsEditor = field.controlType === 'stepsEditor';
        const prop = isStepsEditor
            ? {
                type: 'array',
                description: field.label || name,
                    items: {
                        type: 'object',
                        properties: {
                            step: {
                                type: 'string',
                                description: langData.stepDescription || '',
                            },
                            expect: {
                                type: 'string',
                                description: langData.expectDescription || '',
                            },
                        },
                    },
            }
            : {
                type: 'string',
                description: field.label || name,
            };
        if(!isStepsEditor && Array.isArray(field.options) && field.options.length)
        {
            prop.enum = field.options.map(o =>
            {
                if(typeof o === 'string') return o;
                return o.value !== undefined ? String(o.value) : String(o);
            });
        }
        properties[name] = prop;
        if(field.required) required.push(name);
        if(field.label && field.label !== name) labelToName[field.label] = name;
    });

    const objectSchema = {
        type: 'object',
        properties,
        required,
    };
    const schema = isBatch
        ? {
            type: 'array',
            items: objectSchema,
        }
        : objectSchema;

    const formConfig = getPromptFormConfig(promptFields, {
        title: langData.formFillTitle,
        submitBtnText: langData.submitFormDisplayName,
    });

    const toolName = 'submitFormData';
    const agentToolDef = {
        name: toolName,
        displayName: langData.submitFormDisplayName,
        description: langData.submitFormDescription,
        parameters: {
            type: 'object',
            properties: {
                data: schema,
                title: {
                    type: 'string',
                    description: langData.promptResultTitle,
                },
                summary: {
                    type: 'string',
                    description: langData.agentResultSummary,
                },
            },
            required: ['data', 'summary'],
        },
    };

    const contextLines = [];
    if(contextIDs && typeof contextIDs === 'object')
    {
        Object.keys(contextIDs).forEach(type =>
        {
            const id = contextIDs[type];
            if(id > 0) contextLines.push(`  ${type}：#${id}`);
        });
    }
    const contextStr = contextLines.length ? `${langData.formPageContext}：\n${contextLines.join('\n')}\n` : '';

    let dataPrompt;
    if(isBatch)
    {
        const headers = fields.map(f => f.label || f.name);
        const values  = fields.map(f => f.currentValue ?? '');
        const sepLine = '| ' + headers.map(() => '---').join(' | ') + ' |';

        const fieldDefs = fields.map(f =>
        {
            let def = `- ${f.name}(${f.label || f.name}): ${f.type || f.controlType || 'input'}`;
            if(f.required) def += ' ' + langData.formRequiredField;
            if(Array.isArray(f.options) && f.options.length)
            {
                const opts = f.options.map(o =>
                {
                    const val = (typeof o === 'string') ? o : (o.value !== undefined ? o.value : '');
                    const txt = (typeof o === 'string') ? o : (o.text || o.value || '');
                    return `${val}(${txt})`;
                });
                def += `\n  options: ${opts.join(', ')}`;
            }
            return def;
        }).join('\n');

        dataPrompt = [
            contextStr,
            langData.formCurrentData,
            '',
            '| ' + headers.join(' | ') + ' |',
            sepLine,
            '| ' + values.join(' | ') + ' |',
            '',
            langData.formFieldDefinition,
            fieldDefs,
            '',
            langData.formFillableFields,
            Object.keys(properties).map(n => '- ' + n).join('\n'),
            '',
            langData.formReturnJSONArray,
        ].filter(Boolean).join('\n');
    }
    else
    {
        const fieldsList = fields.map(f =>
        {
            let optionsStr = '';
            if(Array.isArray(f.options) && f.options.length)
            {
                const opts = f.options.map(o =>
                {
                    const val = (typeof o === 'string') ? o : (o.value !== undefined ? o.value : '');
                    const txt = (typeof o === 'string') ? o : (o.text || o.value || '');
                    return `${val}(${txt})`;
                });
                optionsStr = `\n  options: ${opts.join(', ')}`;
            }
            const isSteps = f.controlType === 'stepsEditor';
            const valueType = f.valueType || 'string';
            const currentVal = isSteps && Array.isArray(f.currentValue)
                ? `[${f.currentValue.length} steps]`
                : (typeof f.currentValue === 'object' ? JSON.stringify(f.currentValue) : f.currentValue ?? '');
            return [
                `- ${f.label || f.name}`,
                `  name: ${f.name}`,
                `  input: ${f.type || f.controlType || 'input'}`,
                `  type: ${valueType}`,
                `  required: ${!!f.required}`,
                `  value: ${currentVal}`,
                optionsStr,
            ].filter(Boolean).join('\n');
        }).join('\n');

        const fillableFields = Object.keys(properties).map(n => `- ${n}`).join('\n');

        dataPrompt = [
            contextStr,
            langData.formCurrentData,
            fieldsList,
            '',
            langData.formFillableFields,
            fillableFields,
            '',
            langData.formZentaoAPITip,
        ].filter(Boolean).join('\n');
    }
    const rolePrompt = agentRole || langData.formFillTitle;
    const prompt = [rolePrompt, zui.formatString(langData.processDataPrefix, {data: dataPrompt})].filter(Boolean).join('\n\n');

    const tools = [
        {
            ...agentToolDef,
            fn: (response) =>
            {
                const rawData = response.data;
                const isArrayResult = Array.isArray(rawData);
                let result;
                if(isArrayResult)
                {
                    result = rawData.map(item =>
                        item && typeof item === 'object'
                            ? Object.fromEntries(
                                Object.entries(item)
                                    .map(([key, val]) =>
                                    {
                                        const mappedKey = labelToName[key] || key;
                                        if(mappedKey !== key && item[mappedKey] !== undefined) return null;
                                        return [mappedKey, val];
                                    })
                                    .filter(Boolean)
                            )
                            : item
                    );
                }
                else
                {
                    result = rawData && typeof rawData === 'object'
                        ? Object.fromEntries(
                            Object.entries(rawData)
                                .map(([key, val]) =>
                                {
                                    const mappedKey = labelToName[key] || key;
                                    if(mappedKey !== key && rawData[mappedKey] !== undefined) return null;
                                    return [mappedKey, val];
                                })
                                .filter(Boolean)
                        )
                        : rawData;
                }
                const formPropNames = {};
                if(result && typeof result === 'object')
                {
                    if(isArrayResult)
                    {
                        result.forEach(function(item) {
                            if(item && typeof item === 'object') Object.keys(item).forEach(function(k) { formPropNames[k] = k; });
                        });
                    }
                    else
                    {
                        Object.keys(result).forEach(function(k) { formPropNames[k] = k; });
                    }
                }
                const taskResult = {
                    agentID: 'zentao-api',
                    id: 'zentao-agent-result-' + Date.now(),
                    tool: agentToolDef,
                    title: response.title,
                    result: response,
                    formLocation: window.top ? window.top.location.href : window.location.href,
                    targetFormName: langData.formCurrentTarget,
                    targetForm: 'current',
                    objectID: 0,
                    objectType: 'form',
                    objectData: result,
                    objectProps: {form: formPropNames},
                    actions: [],
                };
                const message = {
                    role: 'user',
                    content: [
                        langData.formResultGenerated,
                        JSON.stringify(result),
                        '',
                        langData.formApplyDataTip,
                    ].join('\n\n'),
                    custom_data: {
                        taskResults: [taskResult],
                        asRole: 'assistant',
                    },
                };
                return {
                    message,
                };
            },
        },
    ];

    const popupOptions = {
        id: 'zentao-prompt-popup',
        viewType: 'chat',
        width: 600,
        postMessage: {
            content: [
                {
                    role: 'user',
                    content: agentPurpose || langData.formFillUserMessage,
                    custom_data: {
                        invisible: true,
                    },
                },
            ],
        },
        creatingChat: {
            agent: 'zentao-api',
            title: langData.formFillTitle,
            prompt: prompt,
            tools: tools,
            form: formConfig,
            skills: Array.isArray(skills) && skills.length ? skills : undefined,
        },
    };

    const popup = zaiPanel.openPopup(popupOptions);
    await new Promise(resolve => requestAnimationFrame(resolve));
    return popup;
};

window.openAITaskPopup = async function(taskID)
{
    const zaiPanel = await checkZAIPanel(true);
    if(!zaiPanel) return;

    const popupOptions = {
        id         : 'zentao-task-popoup',
        viewType   : 'task',
        width      : 600,
        chatID     : `task-${taskID}`,
    };
    zaiPanel.openPopup(popupOptions);
};

window.callZentaoAgent = async function(agentID, objectID)
{
    const res = await $.ajax({url: $.createLink('ai', 'promptExecute', `promptId=${agentID}&objectId=${objectID}`), 'dataType': 'json'});
    if(!res || res.result !== 'success' || !res.callback) return;
    return executeZentaoPrompt(res.callback.params[0], res.callback.params[1]);
};

/* 加载数字员工列表，并注册菜单 */
function loadAndRegisterAiTeammates(lang, plugin)
{
    plugin.defineContextProvider({
        code: 'ai-teammate',
        title: lang.teammate,
        icon: 'hand-right',
        items: async function()
        {
            const res = await zui.fetchData($.createLink('ai', 'ajaxGetTeammates'));
            if(!res || res.result !== 'success' || !res.data) return [];

            const teammates = res.data;
            if(!teammates.length) return;

            const items = teammates.map((item) => {
                const collections = [];
                if(item.klibs && item.klibs.length)
                {
                    item.klibs.forEach(klibID => collections.push(`zentao:${klibID}`));
                }

                const promptParts = [];
                if(item.roleName)
                {
                    const prefix = lang.teammatePromptPrefix;
                    promptParts.push(`${prefix}${item.roleName}`);
                }
                if(item.desc) promptParts.push(item.desc);
                if(item.klibNames && item.klibNames.length)
                {
                    const klibNamesStr = item.klibNames.join(', ');
                    const knowledgePrefix = lang.teammateKnowledgePrefix;
                    const knowledgeSuffix = lang.teammateKnowledgeSuffix;
                    promptParts.push(`${knowledgePrefix}${klibNamesStr}${knowledgeSuffix}`);
                }

                const data = {
                    prompt: promptParts.join(', '),
                };
                if(collections.length) data.memory = {collections};

                return {
                    code: `zentao-aiteammate-${item.id}`,
                    title: item.name,
                    hint: item.desc || item.name,
                    data,
                    llm: item.llm || undefined
                };
            });

            return items;
        },
    });
}

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

    plugin.defineContextProvider({
        code: 'vectorizedData',
        icon: 'db',
        title: lang.vectorizedData,
        when:  ({store}) => !!store.globalMemory,
        items: ['story', 'demand', 'bug', 'doc', 'design', 'feedback', 'all'].map(objectType => {
            if(objectType === 'all') return {
                code : 'globalMemory',
                title: lang.globalMemoryTitle,
                icon : 'book',
                when : context => !!context.store.globalMemory,
                data : {memory: {collections: ['zentao:global']}},
            };
            if(objectType === 'feedback' && !zentaoEdition) return;
            if(objectType === 'demand' && zentaoEdition !== 'ipd') return;
            return {
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
            };
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

    window.enableAITeammate && loadAndRegisterAiTeammates(lang, plugin);

    plugin.defineSuggestion(
    {
        when: ({state}) =>
        {
            const page = state ? state.zentaoPage : null;
            if(!page) return;
            const openedApp = $.apps.openedApps[page.app];
            if(!openedApp) return;
            const aiSuggestions = openedApp.iframe.contentWindow ? openedApp.iframe.contentWindow.aiSuggestions : null;
            return Array.isArray(aiSuggestions) && aiSuggestions.length;
        },
        items: function({state})
        {
            const zentaoPage = state ? state.zentaoPage : null;
            if(!zentaoPage) return;
            const openedApp = $.apps.openedApps[zentaoPage.app];
            if(!openedApp) return;
            const aiSuggestions = openedApp.iframe.contentWindow ? openedApp.iframe.contentWindow.aiSuggestions : null;
            return aiSuggestions.map(suggestion => {
                const {page = '', zentaoAgent, ...others} = suggestion;
                const pageList = page.split(',').filter(Boolean);
                if(pageList && !pageList.some(x => x === zentaoPage.path || x === zentaoPage.currentModule)) return;
                return {
                    ...others,
                    ...(zentaoAgent ? {action: () => callZentaoAgent(zentaoAgent.agentID, zentaoAgent.objectID)} : {}),
                };
            }).filter(Boolean);
        }
    });

    plugin.defineCallback('onCreateChat', async function(info)
    {
        if(info.isLocal) return;

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
        if(!info.postingMessages || !info.postingMessages.length) return;
        if(!info.chat.custom_data || !info.chat.custom_data.ztklibs) return;

        const userPrompts   = [];
        const systemPrompts = [];
        info.postingMessages.forEach(x =>
        {
            if(x.role === 'user')        userPrompts.push(x.content);
            else if(x.role === 'system') systemPrompts.push(x.content);
        });
        let searchPrompt = userPrompts.filter(Boolean).join('\n').trim();
        if(!searchPrompt.length) searchPrompt = systemPrompts.filter(Boolean).join('\n').trim();
        if(!searchPrompt.length) return;

        info.updateState(lang.searchingKLibs);

        const ztklibs  = info.chat.custom_data.ztklibs;
        const ztChunks = info.chat.$local.ztChunks || {};
        const [response] = await $.ajaxSubmit(
        {
            url:  $.createLink('zai', 'ajaxSearchKnowledges'),
            data: {userPrompt: searchPrompt, filters: JSON.stringify(ztklibs)}
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
    const isOpenVersion = /^\d/.test(config.version || $('#zuiCSS').attr('href').split('?v=').pop());
    if(zaiConfig)
    {
        registerZentaoAIPlugin(zaiLang);

        let userAvatarProps;
        const getAvatar = (type, info) =>
        {
            if(type === 'role' && info.role === 'user')
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
                }
                return userAvatarProps;
            }
            if(type === 'chat' && info.chat.teammate)
            {
                const teammate = zaiConfig.teammateMap[info.chat.teammate] || {id: info.chat.teammate, name: info.chat.teammate};
                return {src: teammate.avatar, size: 24, code: teammate.id};
            }
        };
        const aiStore = zui.ZAIStore.createFromZentao($.extend({
            getAvatar: getAvatar,
            onSelectExternalSkill: zaiConfig.canAddSkill ? ((chat, selectSkill) => {
                const callbackID = `skillOnSelect${zui.nextGid()}`;
                window[callbackID] = (skill) => {
                    selectSkill({id: skill.skillID, name: skill.name});
                    if(!aiStore.externalSkillsMap) aiStore.externalSkillsMap = {};
                    aiStore.externalSkillsMap[skill.skillID] = skill;
                    delete window[callbackID];
                };
                zui.Modal.open({
                    id: 'selectSkillModal',
                    url: $.createLink('ai', 'selectSkill', `callback=${callbackID}`),
                    size: 'sm',
                    onHidden: () => {
                        delete window[callbackID];
                    }
                });
            }) : undefined,
            onMountExternalSkill: zaiConfig.canAddSkill ? (async (chat, skill) => {
                const externalSkill = aiStore.externalSkillsMap ? aiStore.externalSkillsMap[skill.id] : null;
                const result = await $.post($.createLink('ai', 'addSkill', `skillID=${zui.encodeBase64(externalSkill ? externalSkill.id : skill.id)}`), undefined, 'json');
                return typeof result === 'object' && result.result !== 'fail';
            }) : undefined,
            fetchMySkills: async () => {
                const result = await zui.fetchData($.createLink('ai', 'ajaxGetMySkills'));
                const skills = (result.skills || []).map(skill => ({id: skill.skillID, description: skill.desc, name: skill.name}));
                return skills;
            },
            chatAgent: zaiConfig.userAgent || (async () => {
                if(zaiConfig.userAgent) return zaiConfig.userAgent;

                const result = await zui.fetchData($.createLink('zai', 'ajaxGetUserAgent'));
                return result.data;
            })
        }, zaiConfig));
        if(!aiStore) return

        zui.AIPanel.init(
        {
            store            : aiStore,
            position         : {bottom: +window.config.debug > 4 ? 56 : 40, right: 16},
            maximizedPosition: {left: 'calc(var(--zt-menu-width) + 4px)', top: 4, bottom: 'calc(var(--zt-apps-bar-height) + 4px)', right: 16},
            langData         : zaiLang,
            getErrorContent: (error) =>
            {
                let html = '';
                if(error.type === 'unauthorized' && zaiLang.unauthorizedError) html = zui.formatString(zaiLang.unauthorizedError, {zaiConfigUrl: $.createLink('zai', 'setting')})
                else if(error.type === 'configNotValid' && zaiLang.zaiConfigNotValid) html = zui.formatString(zaiLang.zaiConfigNotValid, {zaiConfigUrl: $.createLink('zai', 'setting')})

                if(html.length) return {html: `<div class="row gap-3"><i class="mt-1 icon icon-exclamation text-warning"></i><div class="text-left pr-8">${html}</div></div>`};
                return error.message;
            },
            tabs: !window.enableAITeammate ? undefined : [
                {key: 'RECENTS', title: zaiLang.recentChats, chatTypes: ['chat']},
                {key: 'TASKS', title: zaiLang.aiTeammateTasks, chatsFetcher: (store) => store.getTasks(), onCreate: false, searchBox: {placeholder: zaiLang.searchTasks}},
            ],
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

        aiStore.waitInited().then(() => {window.isZaiOK = aiStore.ok;});
    }

    /* Bind AI commands in app when app is loaded. */
    $(document).on('loadapp.apps', (_, args) =>
    {
        setTimeout(() => bindAICommandsInApp(args[0].iframe.contentWindow), 1000);
    });
});
