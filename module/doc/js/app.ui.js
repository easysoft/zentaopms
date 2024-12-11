function isApiLib(lib, docApp)
{
    if(docApp && docApp.spaceType === 'api') return true;
    if(typeof lib === 'number') lib = getDocApp().getLib(lib);
    if(!lib)
    {
        docApp = docApp || getDocApp();
        if(!docApp) return false;
        if(docApp.spaceType === 'api') return true;
        lib = docApp.lib;
    }
    return lib && lib.data.type === 'api';
}

$.extend(window.docAppActions,
{
    'doc-edit': function(info)
    {
        const doc = info.data;
        if(!doc) return;

        const lang = getLang();
        if(isApiLib())
        {
            return [
                {text: lang.save, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveApiDoc'},
                {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
            ];
        }
        return [
            {text: lang.template, size: 'md', className: 'btn-wide', type: 'primary-outline', icon: 'save', command: 'selectTemplate'},
            doc.status === 'draft' ? {text: lang.saveDraft, size: 'md', className: 'btn-wide', type: 'secondary', command: 'saveDoc/draft'} : null,
            {text: lang.release, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveDoc'},
            {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelEditDoc'},
            {text: lang.settings, size: 'md', type: 'ghost', command: `showDocSettingModal/${doc.id}/${doc.contentType}/1`, icon: 'cog-outline'},
        ];
    },
    'doc-create': function(info)
    {
        const doc = info.data;
        if(!doc) return;

        const lang = getLang();
        if(isApiLib())
        {
            return [
                {text: lang.save, size: 'md', className: 'btn-wide', type: 'primary', command: 'saveApiDoc'},
                {text: lang.cancel, size: 'md', className: 'btn-wide', type: 'primary-outline', command: 'cancelCreateDoc'},
            ];
        }
        return [
            {text: lang.template, size: 'md', className: 'btn-wide', type: 'primary-outline', icon: 'save', command: 'selectTemplate'},
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
