(function()
{
    const topWindow = window.top || window;
    const guard =
    {
        showing: false,
        modal: null
    };

    function isImportProgressPage()
    {
        return $('.repo-import-progress').length > 0;
    }

    function showLeaveTip()
    {
        if(!isImportProgressPage()) return false;
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
        if(!isImportProgressPage()) return;

        event.preventDefault();
        event.returnValue = importProgressLeaveTip;
        return importProgressLeaveTip;
    }

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

    if(window.repoImportProgressBeforeUnloadHandler)
    {
        window.removeEventListener('beforeunload', window.repoImportProgressBeforeUnloadHandler);
        if(topWindow !== window) topWindow.removeEventListener('beforeunload', window.repoImportProgressBeforeUnloadHandler);
    }

    window.repoImportProgressBeforeUnloadHandler = handleBeforeUnload;
    window.addEventListener('beforeunload', handleBeforeUnload);
    if(topWindow !== window) topWindow.addEventListener('beforeunload', handleBeforeUnload);

    window.onCloseApp = function()
    {
        if(!isImportProgressPage()) return;
        showLeaveTip();
        return false;
    };
})();
