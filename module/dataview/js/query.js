$(function()
{
    var fileName = dataview != '' ? dataview.name : '';
    var exportModal = new $.zui.ModalTrigger({url: createLink('dataview', 'export', 'type=query&table=&fileName=' + fileName, '', true), type: 'iframe', width: 900});
    if(document.getElementById('exportDataview'))
    {
        document.getElementById('exportDataview').addEventListener('click', function(e)
        {
            tryQuery(e);
            showModel(exportModal);
        });
    }

    if(rawMethod !== 'create') return;
    var createModal = new $.zui.ModalTrigger({url: createLink('dataview', 'create', 'step=create', '', true), type: 'iframe', width: 480});
    document.getElementById('saveButton').addEventListener('click', function(e)
    {
        tryQuery(e);
        showModel(createModal);
    });
});

function tryQuery(e)
{
    $.ajaxSettings.async = false;
    query();
    $.ajaxSettings.async = true;
    e.preventDefault();
}

function showModel(objModel)
{
    if($('#dataform .error').length == $('#dataform .error.hidden').length)
    {
        var params = $('#dataform').serializeArray();

        getFieldSettings();

        /* Fix bug #26716. */
        var fieldSettings = DataStorage.clone('fieldSettings');
        for(let index in fieldSettings)
        {
            if(!Object.keys(DataStorage.fields).includes(index)) delete fieldSettings[index];
        }
        DataStorage.fieldSettings = fieldSettings;

        sessionStorage.setItem('dataviewSql', JSON.stringify(params));
        sessionStorage.setItem('fieldSettings', JSON.stringify(fieldSettings));
        sessionStorage.setItem('langs', JSON.stringify(DataStorage.langs));

        objModel.show();
    }
}

function locate(method, params)
{
    var link = createLink('dataview', method, params);
    window.location.href = link;
}
