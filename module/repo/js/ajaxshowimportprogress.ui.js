(function()
{
    const topWindow = window.top || window;
    const pollingInterval = 1000;
    const guard =
    {
        showing: false,
        modal: null,
        completed: false
    };
    let timer = 0;
    let polling = false;

    function isImportProgressPage()
    {
        return $('.repo-import-progress').length > 0;
    }

    function showLeaveTip()
    {
        if(!isImportProgressPage()) return false;
        if(guard.completed) return false;
        if(guard.showing) return true;

        guard.showing = true;
        guard.modal = zui.Modal.confirm(
        {
            message: importProgressLeaveTip,
            closeBtn: false,
            actions:
            [
                {
                    text: importProgressAcknowledge,
                    key: 'confirm'
                }
            ],
            onResult: function()
            {
                guard.showing = false;
                guard.modal = null;
            }
        });

        return true;
    }

    function handleBeforeUnload(event)
    {
        if(!isImportProgressPage() || guard.completed) return;

        event.preventDefault();
        event.returnValue = importProgressLeaveTip;
        return importProgressLeaveTip;
    }

    function removeBeforeUnload()
    {
        if(!window.repoImportProgressBeforeUnloadHandler) return;

        window.removeEventListener('beforeunload', window.repoImportProgressBeforeUnloadHandler);
        if(topWindow !== window) topWindow.removeEventListener('beforeunload', window.repoImportProgressBeforeUnloadHandler);
        delete window.repoImportProgressBeforeUnloadHandler;
    }

    function clearPolling()
    {
        if(timer)
        {
            clearTimeout(timer);
            timer = 0;
        }
        polling = false;
    }

    function handleCloseApp()
    {
        if(!isImportProgressPage() || guard.completed) return;
        showLeaveTip();
        return false;
    }

    function cleanupImportProgressPage()
    {
        clearPolling();
        removeBeforeUnload();
        $(document).off('.repoImportProgressGuard');

        if(guard.modal)
        {
            guard.modal.hide();
            guard.modal = null;
        }

        guard.showing = false;

        if(window.onCloseApp === handleCloseApp) delete window.onCloseApp;
    }

    function bindLeaveProtection()
    {
        $(document).off('.repoImportProgressGuard');

        $(document).on('click.repoImportProgressGuard', 'a,.open-url,[data-url]', function(event)
        {
            if(!isImportProgressPage()) return;

            const $target = $(event.currentTarget);
            if($target.is('[data-toggle],[data-on],[target],.iframe,.not-open-url,.show-in-app')) return;

            const href = $target.attr('href');
            const dataUrl = $target.data('url');
            const url = dataUrl || href;

            if(!url || url[0] === '#' || /^javascript:/i.test(url)) return;

            event.preventDefault();
            event.stopImmediatePropagation();
            showLeaveTip();
        });

        removeBeforeUnload();
        window.repoImportProgressBeforeUnloadHandler = handleBeforeUnload;
        window.addEventListener('beforeunload', handleBeforeUnload);
        if(topWindow !== window) topWindow.addEventListener('beforeunload', handleBeforeUnload);

        window.onCloseApp = handleCloseApp;
    }

    function initImportProgressPage()
    {
        if(!isImportProgressPage())
        {
            cleanupImportProgressPage();
            return;
        }

        bindLeaveProtection();
        if(!guard.completed) schedulePolling();
    }

    function schedulePolling()
    {
        clearPolling();
        if(guard.completed) return;
        timer = setTimeout(pollImportProgress, pollingInterval);
    }

    function finishImport()
    {
        if(guard.completed) return;

        guard.completed = true;
        clearPolling();
        if(guard.modal)
        {
            guard.modal.hide();
            guard.modal = null;
        }
        guard.showing = false;

        if(typeof loadPage === 'function')
        {
            loadPage(importProgressListLink);
            return;
        }

        window.location.href = importProgressListLink;
    }

    async function pollImportProgress()
    {
        if(!isImportProgressPage() || guard.completed || polling) return;

        polling = true;
        try
        {
            const response = await fetch(importProgressPollingLink, {credentials: 'same-origin'});
            const result = await response.json();
            if(result && result.data && (result.data.status === 'finished' || result.data.status === 'failed'))
            {
                finishImport();
                return;
            }
        }
        catch(error)
        {
            /* Keep polling on transient failures. */
        }
        finally
        {
            polling = false;
        }

        schedulePolling();
    }

    window.onPageUnmount = cleanupImportProgressPage;
    window.afterPageRender = initImportProgressPage;

    initImportProgressPage();
})();
