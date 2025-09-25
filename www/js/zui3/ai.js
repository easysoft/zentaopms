window.checkZAIPanel = async function(showMessage)
{
    const zaiPanel = zui.AIPanel.shared;
    const store = zaiPanel ? zaiPanel.store : null;
    if(!store || !store.isConfigOK)
    {
        if(showMessage) zui.Modal.alert(store.error || zaiConfig.langData.zaiConfigNotValid);
        return;
    }
    const isOK = await store.isOK();
    if(!isOK)
    {
        if(showMessage) zui.Modal.alert(store.error || zaiConfig.langData.unauthorizedError);
        return;
    }
    return zaiPanel;
};

window.openPageForm = function(url, data, callback)
{
    const openedApp = $.apps.openApp(url);
    const handlePageLoad = () =>
    {
        setTimeout(() => {
            try {
                const iframe = openedApp.iframe;
                iframe.contentWindow.applyFormData(data);
                callback && callback();
            } catch (error) {}
        }, 2000);
    };
    openedApp.$app.one('updateapp.apps updatepage.app', handlePageLoad);
    setTimeout(() => openedApp.$app.off('updateapp.apps', handlePageLoad), 5000);
}

window.executeZentaoPrompt = async function(info)
{
    const zaiPanel = await checkZAIPanel(true);
    if(!zaiPanel) return;

    const tools = [{
        name       : `zentao_prompt_${info.promptID}`,
        displayName: info.name,
        description: info.name,
        parameters : info.schema,
        fn: (result) => {
            const targetForm = info.targetForm;
            if(!targetForm) return {result: result};

            const langData        = zaiPanel.options.langData || {};
            const applyFormFormat = langData.applyFormFormat;
            return {
                actions: [{
                    text        : (applyFormFormat || '%s').replace('%s', info.targetFormName || info.targetForm),
                    onClick     : () => openPageForm(info.formLocation, result, () => zui.Messager.success(langData.applyFormSuccess.replace('%s', info.targetFormName || info.targetForm))),
                    type        : 'primary-pale',
                    trailingIcon: 'icon-arrow-right'
                }]
            };
        },
    }];
    const postMessage = {
        content: info.name,
        prompt : info.prompt,
        tools  : tools,
        model  : info.model,
    };
    zaiPanel.openPopup({viewType: 'chat', width: 600, postMessage: postMessage});
};

function registerZentaoAIPlugin(lang)
{
    const plugin = zui.AIPlugin.define('zentao', {name: lang.name, icon: 'zentao'});

    plugin.defineAgent('storyReviewer',
    {
        name:    lang.storyReview,
        alias:   [lang.storyReview, 'storyReview'],
        welcome: '👋 您好，欢迎使用禅道需求评审专家。\n我将帮助您精准分析需求，提升质量并确保可执行性。\n请提供您想要评审的需求内容，我将立即开始评审并提供针对性建议。\n我们的目标是：\n- 快速识别需求中的关键问题\n- 提供清晰的改进方向\n- 使需求更具可实现性与高效交付性\n\n请随时向我提出任何问题， 我将在每一步为您提供帮助。',
        prompt: '# 角色定位\n\n\n\n你是一位资深的需求评审专家，专注于帮助项目管理团队提升需求条目的完整性、逻辑性与可实现性。\n\n你的职责是基于专业评审方法，在一次完整分析中，系统性提出归类清晰、重点突出的评审建议，引导用户高效完善需求。如果用户问询需求评审以外的问题，可以给与简单响应后，拉回需求评审中\n\n\n\n# 核心专业能力\n\n- 结构完整性分析（角色、动作、目标、约束要素）\n\n- 逻辑一致性分析（单条及多条需求之间的合理性）\n\n- 评审标准适配（INVEST、SMART或自定义标准）\n\n- 优先级归类评审建议（突出最关键问题，辅助次要优化）\n\n- 正式、清晰、专业的输出风格（无emoji，注重结构）\n\n\n\n# 工作风格\n\n- 一轮输出，结构化归类\n\n- 引导用户先聚焦最关键问题\n\n- 提供清晰、专业、可操作的完善方向\n\n- 语言正式、客观，保持体验流畅且不压迫\n\n\n\n\n\n# 评审交互流程\n\n1. 接收需求文本（来源于需求详情点击或输入）\n\n2. 进行结构与逻辑完整性检查\n\n3. 按重要性将评审建议归为两大类：\n\n- 核心优先改进项（必须优先处理）\n\n- 次要优化建议（在有时间或资源时进一步完善）\n\n4. 一次性输出完整评审结果，不进行多轮追问\n\n5. 引导用户先集中处理核心问题，如有余力再逐步优化次要问题\n\n\n\n# 输出结构要求\n\n\n\n- 统一使用正式Markdown结构，分清主次\n\n- 首先输出【评审总结】，概述整体需求状态\n\n- 然后分为两个部分输出：\n\n- 第一部分：核心优先改进项\n\n- 第二部分：次要优化建议\n\n- 每个建议应简明扼要，突出问题与优化方向\n\n- 不使用emoji或花哨符号，保持专业正式风格\n\n\n\n# 输出示范结构\n\n\n\n# 需求评审结果总结\n\n\n\n本次评审分析显示，需求整体情况如下：\n\n- 结构完整性：基本完整/存在缺失\n\n- 逻辑一致性：连贯/存在冲突\n\n- 标准符合性（如INVEST）：符合/部分符合/存在明显缺口\n\n\n\n# 核心优先改进项\n\n\n\n以下问题建议优先处理，以保证需求的可实现性与后续交付质量：\n\n\n\n1. （最重要问题简述）\n\n2. （次重要问题简述）\n\n3. （其他关键问题简述）\n\n\n\n# 次要优化建议\n\n\n\n在核心问题处理完毕后，可进一步关注以下细节优化：\n\n\n\n1. （次要问题简述）\n\n2. （细节补充建议）\n\n3. （未来增强方向提示）\n\n\n\n# 小结\n\n\n\n根据用户本次的改进项目，给出总结。\n\n\n\n# 附加控制策略\n\n- 若检测到需求长度或复杂度超出正常范围，可适度缩减次要优化建议，只列出最相关的补充方向。\n\n- 若累计对话Token数接近10000时，友好提示建议保存成果并新开对话，避免性能下降'
    });

    plugin.defineMethod('reviewStory', function({state, panel}, story)
    {
        if(!story)
        {
            const page$ = $.apps.getLastApp().iframe.contentWindow.$;
            story = state?.story || {
                title:  page$('#mainContent').find('.detail-header .entity-title-text').text(),
                spec:   page$('#mainContent').find('.detail-body').find('.detail-section[zui-key="spec"],.detail-section[zui-key="需求描述"],.detail-section[zui-key="Description"]').find('.detail-section-content').text(),
                verify: page$('#mainContent').find('.detail-body').find('.detail-section[zui-key="verify"],.detail-section[zui-key="验收标准"],.detail-section[zui-key="Acceptance"]').find('.detail-section-content').text(),
            };
        }

        panel && panel.openPopup({
            viewType:    'chat',
            postMessage: `/storyReviewer ${zui.formatString(lang.storyReviewMessage, story)}`,
            viewProps:   {mode: 'simple'}
        });
    });

    plugin.defineSuggestion(
    {
        title   : lang.storyReview,
        icon    : 'lightbulb',
        type    : 'zentao',
        priority: 5,
        command : '.reviewStory',
        hint    : lang.storyReviewHint,
        when    : ({state}) => {
            const page = state?.zentaoPage;
            return page && page.path === 'story-view';
        },
    });

    plugin.bindEvent('updatepage', function(_context, data)
    {
        if(data.page.path === 'story-view')
        {
            const pageWindow         = $.apps.getLastApp().iframe.contentWindow;
            const page$              = pageWindow.$;
            const $firstSectionTitle = page$('#mainContent .detail-sections[zui-key="main"] > .detail-section').first().children('.detail-section-title,.flex.items-center').first();
            if(!$firstSectionTitle.length) return;

            let $injectActions = $firstSectionTitle.find('.ai-inject-actions');
            if(!$injectActions.length)
            {
                $injectActions = $(`<div class="ai-inject-actions flex-none"><button class="btn ai-styled size-sm ml-2" type="button" zui-command="ai~zentao.reviewStory">${lang.aiReview}</button></div>`).appendTo($firstSectionTitle);
                $firstSectionTitle.find('span').first().addClass('flex-auto');
            }
        }
    });

    plugin.defineContextProvider(
    {
        code: 'currentPage',
        title: lang.currentPage,
        icon: 'globe',
        recommend: true,
        when: () => $.apps,
        data: () => {
            const pageWindow = $.apps.getLastApp().iframe.contentWindow;
            const page$ = pageWindow.$;
            const $mainContainer = page$('#mainContainer');
            const pageContent = $mainContainer.length ? $mainContainer.text() : page$('body').text();
            return {
                prompt: [
                    `当前页面标题：${document.title}`,
                    "当前页面内容：",
                    pageContent
                ].join(TWO_BREAKS)
            };
        },
        generate: (userPrompt, {plugin}) => {
            if(new RegExp(`@(${lang.currentPage})`, 'i').test(userPrompt)) return {};
        }
    })

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

    ["story", "demand", "bug", "doc", "design", "feedback"].forEach(objectType => {
        if(objectType === "feedback" && !zentaoEdition) return;
        if(objectType === "demand" && zentaoEdition !== "ipd") return;
        plugin.defineContextProvider({
            code: `${objectType}Lib`,
            title: lang[objectType],
            icon:  objectIcons[objectType],
            when:  ({store}) => !!store.globalMemory,
            data:
            {
                memory: {collections: ['$global'], content_filter: {attrs: {objectType}}},
            },
            generate: (userPrompt, { plugin }) => {
                const objectName = plugin?.getLang(objectType) ?? objectType;
                const matches = [...userPrompt.matchAll(new RegExp(`@(${objectName}${objectType !== objectName ? `|${objectType}` : ''})\\s?#?(\\d+)`, 'gi'))];
                if(matches.length)
                {
                    return matches.map(match => {
                        const objectID = match[2];
                        return {
                            code: `${objectType}-${objectID}`,
                            recommend: true,
                            title: `${objectName} #${objectID}`,
                            data: () => ({
                                memory:
                                {
                                    collections:    ['$global'],
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
            return {prompt: ["当前文档内容：", text].join(TWO_BREAKS)};
        },
        generate: (userPrompt, { plugin }) => {
            if (new RegExp(`@(${lang.currentDocContent})`, 'i').test(userPrompt)) return {};
        }
    });

    plugin.defineContextProvider({
        code : 'globalMemory',
        title: lang.globalMemoryTitle,
        icon : 'book',
        when : context => !!context.store.globalMemory,
        data : {memory: {collections: ['$global']}},
    });
}

$(() => {
    if(getZentaoPageType() !== 'home')
    {
        const panel = zui.AIPanel.shared;
        if(panel)
        {
            zui.bindCommands(document.body, {
                commands: {},
                scope: panel.commandScope,
                onCommand: panel.executeCommand.bind(panel)
            });
        }
        return;
    }

    const zentaoConfig = window.config
    if(!zentaoConfig || zentaoConfig.currentModule !== 'index' || zentaoConfig.currentMethod !== 'index') return;

    const zaiConfig = window.zai || window.top.zai;
    if(zaiConfig)
    {
        const langData = zaiConfig.langData;
        registerZentaoAIPlugin(langData);

        const aiStore = zui.ZAIStore.createFromZentao(zaiConfig);
        if(!aiStore) return

        let userAvatarProps;
        const aiPanel = zui.AIPanel.init(
        {
            store:    aiStore,
            position: {left: 24, top: 24, bottom: +window.config.debug > 4 ? 56 : 40, right: 16},
            langData: langData,
            getAvatar: (info, props) =>
            {
                if(info.role === 'user')
                {
                    if(userAvatarProps) return userAvatarProps;
                    const $avatar = $.apps.getLastApp().iframe?.contentWindow.$('#userMenu-toggle>.avatar');
                    if($avatar?.length)
                    {
                        userAvatarProps =
                        {
                            text: $avatar.find('.avatar-text').text(),
                            code: window.config.account,
                            src: $avatar.find('img').attr('src'),
                            icon: undefined,
                            background: $avatar.css('backgroundColor'),
                            foreColor: $avatar.css('color'),
                        };
                        return userAvatarProps;
                    }
                }
                return props;
            },
            getErrorContent: (error) =>
            {
                let html = '';
                if(error.type === 'unauthorized' && langData.unauthorizedError) html = zui.formatString(langData.unauthorizedError, {zaiConfigUrl: $.createLink('zai', 'setting')})
                else if(error.type === 'configNotValid' && langData.zaiConfigNotValid) html = zui.formatString(langData.zaiConfigNotValid, {zaiConfigUrl: $.createLink('zai', 'setting')})

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
    }
});
