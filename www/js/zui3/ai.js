$(() => {
    const AIPanel       = zui.AIPanel;
    const ZentaoAIStore = zui.ZentaoAIStore;
    const pageType      = getZentaoPageType();

    const zentaoConfig = window.config
    if(!zentaoConfig || zentaoConfig.currentModule !== 'index' || zentaoConfig.currentMethod !== 'index') return;

    const zaiConfig = window.zai || window.top.zai;
    if(zaiConfig)
    {
        const langData = zaiConfig.langData;
        registerZentaoAIPlugin(langData);

        const aiStore = ZentaoAIStore.createFromZentao(zaiConfig);
        if(!aiStore) return

        let userAvatarProps;
        const aiPanel = AIPanel.init(
        {
            store:    aiStore,
            position: {left: 24, top: 24, bottom: +window.config.debug > 4 ? 56 : 40, right: 16},
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
        });
});
