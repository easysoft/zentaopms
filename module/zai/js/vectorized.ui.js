class VectorizedPanel extends zui.Component
{
    static NAME = 'VectorizedPanel';

    get actualStatus()
    {
        if(!this.isStoreOk) return 'unavailable';
        let status = this.options.status;
        if(status === 'syncing' && !this.syncing) status = 'paused';
        return status;
    }

    init()
    {
        this.commandScope     = 'vectorizedpanel';
        this.isLoading        = false;
        this.syncing          = false;
        this.syncingTimer     = 0;
        this.progressBarWidth = 700;
        this.waitTime         = 0;
        this.syncCouter       = 0;

        let store = null;
        if(window !== window.parent)
        {
            const zaiPanel = window.parent.zui.AIPanel.shared;
            store = zaiPanel ? zaiPanel.store : null;
        }
        else if(this.options.zaiSetting)
        {
            try {
                store = zui.ZentaoAIStore.createFromZentao(this.options.zaiSetting);
            } catch (error) {
                console.error('create zentao ai store failed', error);
            }
        }

        this.store = store;
    }

    async afterInit()
    {
        this.$panel = this.$element.closest('.vectorized-panel');

        this.toggleLoading();
        if (this.store) this.isStoreOk = await this.store.isOK();
        this.toggleLoading(false);

        zui.bindCommands(this.$panel, {
            scope: this.commandScope,
            onCommand: this.executeCommand.bind(this),
        });

        this.render();
    }

    updateProgresses(status)
    {
        status = status || this.actualStatus;
        const options = this.options;
        const syncDetails = options.syncDetails || {};
        const $progresses = this.$panel.find('.vectorized-progress');
        const maxCount = Math.max(100, Object.values(syncDetails).reduce((maxVal, info) => {
            maxVal = Math.max(maxVal, (info.failed || 0) + (info.synced || 0));
            return maxVal;
        }, 0));
        const maxWidth = this.progressBarWidth || 600;
        const syncing = !!this.syncing;
        $progresses.each(function()
        {
            const $progress = $(this);
            const type = $progress.data('type');
            const info = syncDetails[type] || {failed: 0, synced: 0};
            const isSyncing = type === options.syncingType && syncing;
            const synced = info.synced || 0;
            const failed = info.failed || 0;
            const total = synced + failed;
            $progress.toggleClass('is-syncing-type', isSyncing);
            $progress.find('.vectorized-failed-info').toggleClass('hidden', !info.failed);
            $progress.find('.vectorized-finished-count').text(synced);
            $progress.find('.vectorized-failed-count').text(failed);
            $progress.find('.vectorized-loading-icon').toggleClass('hidden', !isSyncing);
            $progress.find('.vectorized-sync-type').toggleClass('font-bold', !!syncDetails[type]).toggleClass('text-gray', !syncDetails[type]).toggleClass('text-primary', isSyncing);
            $progress.find('.vectorized-sync-progress').toggleClass('active progress-striped', isSyncing).css('width', Math.max(1, maxWidth * total / maxCount));
            $progress.find('.progress-bar.is-synced').css('width', total ? `${100 * synced / total}%` : '1px');
            $progress.find('.progress-bar.is-failed').css('width', total ? `${100 * failed / total}%` : '0');
        });
    }

    render(newOptions)
    {
        super.render(newOptions);

        const $panel  = this.$panel;
        const options = this.options;
        const status  = this.actualStatus;
        const lang    = options.langData;
        const syncFailed = status === 'synced' && !this.options.syncedCount && !!this.options.syncFailedCount;

        Object.keys(lang.vectorizedStatusList).forEach(statusType =>
        {
            $panel.toggleClass(`is-status-${statusType}`, status === statusType).toggleClass(`not-status-${statusType}`, status !== statusType);
        });

        $panel.removeClass('loading');
        $panel.find('.vectorized-status').text(lang.vectorizedStatusList[syncFailed ? 'failed' : status]);
        $panel.toggleClass('is-syncing-loop', !!this.syncing);
        $panel.toggleClass('not-syncing-loop', !this.syncing);
        $panel.toggleClass('is-synced-failed', syncFailed).toggleClass('not-synced-failed', !syncFailed);
        $panel.find('.vectorized-finished-total-count').text(options.syncedCount);
        $panel.find('.vectorized-failed-total-count').text(options.syncFailedCount);
        $panel.find('.vectorized-last-sync-info').toggleClass('hidden', status !== 'paused' && status !== 'synced').find('.vectorized-last-sync-time').text(zui.formatDate(status === 'synced' ? (options.syncedTime || options.syncTime) : options.syncTime));
        $panel.find('.vectorized-synced-with-failed').toggleClass('hidden', options.syncFailedCount === 0 || status !== 'synced');

        this.updateProgresses();
    }

    executeCommand(context, params)
    {
        if(typeof context === 'string') context = {name: context};
        if (!context.scope || context.scope === this.commandScope)
        {
            return zui.deepCall(this, context.name, params);
        }
    }

    toggleLoading(loading)
    {
        loading = loading === undefined ? true : !!loading;
        this.isLoading = loading;
        this.$panel.toggleClass('loading', loading);
    }

    getLang(key)
    {
        return this.options.langData[key];
    }

    async request(url, options)
    {
        if(Array.isArray(url)) url = $.createLink.apply(null, url);

        const result = await $.ajaxSubmit($.extend({url: url}, options));
        const response = result[0];
        const error = result[1];
        if(!error && response && response.info) this.render(response.info);

        return response;
    }

    async post(url, data, options)
    {
        return this.request(url, $.extend({method: 'POST', data: data}, options));
    }

    async enable()
    {
        this.toggleLoading(true);
        const result = await this.post(['zai', 'ajaxEnableVectorization']);
        this.toggleLoading(false);
        if(result) return true;
    }

    async syncNext(data)
    {
        this.syncCouter++;
        const result = await this.post(['zai', 'ajaxSyncVectorization'], data);
        let waitTime = result.data.lastSync ? (result.data.lastSync.time * 3) : 100;
        if(this.syncCouter % 200 === 0)  waitTime += 2000;
        if(this.syncCouter % 1000 === 0) waitTime += 2000;
        await zui.delay(waitTime);
        return result;
    }

    async sync(options)
    {
        this.syncing = true;
        this.render();

        const force = options && options.force;
        const result = await this.syncNext({force: !!force});
        if(!result || typeof result !== 'object')
        {
            zui.Messager.fail(this.getLang('syncRequestFailed'));
            this.syncing = false;
            this.render();
            return;
        }
        if(result.result === 'failed')
        {
            this.syncing = false;
            this.render(result.data);
            return;
        }
        if(result.data)
        {
            if(result.data.status === 'synced')
            {
                this.syncing = false;
            }
            else
            {
                this.syncingTimer = setTimeout(() => {
                    this.syncingTimer = 0;
                    if(this.syncing) this.sync();
                }, this.waitTime || 0);
            }
            this.render(result.data);
        }
    }

    async startSync()
    {
        if(this.syncing) return;
        this.sync();
    }

    resumeSync()
    {
        return this.startSync();
    }

    pauseSync()
    {
        if(this.syncingTimer)
        {
            clearTimeout(this.syncingTimer);
            this.syncingTimer = 0;
        }
        this.syncing = false;
    }

    resync()
    {
        if(this.syncing) return;
        return this.sync({force: true});
    }

    async resetSync()
    {
        const confirmed = await zui.Modal.confirm(this.getLang('confirmResetSync'));
        if(!confirmed) return;

        this.toggleLoading(true);
        const result = await this.post(['zai', 'ajaxEnableVectorization'], {force: true});
        this.toggleLoading(false);
        return result;
    }

    destroy()
    {
        super.destroy();
        zui.unbindCommands(this.$panel, this.commandScope);
    }
};

VectorizedPanel.register();

window.updateVectorizedState = function($element)
{
    const panel = $element.find('[z-use-vectorizedpanel]').zui();
    if(panel) panel.render();
};
