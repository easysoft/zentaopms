$.extend(window.docAppActions,
{
    'doc-create': function(info)
    {
        const doc = info.data;
        if(!doc) return;

        const lang = getLang();
        return [
            {text: lang.template, size: 'md', className: 'btn-wide', type: 'secondary', command: 'selectTemplate'},
            {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveNewDoc/draft'},
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveNewDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelCreateDoc'},
        ];
    }
});

$.extend(window.docAppCommands,
{
    selectTemplate: function(_, args)
    {
        const url = $.createLink('doc', 'selectTemplate');
        zui.Modal.open({size: 'sm', url:  url});
    }
});
