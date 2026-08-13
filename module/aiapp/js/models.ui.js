function updateMenuLabel(modelType, count)
{
    const $navItem = $(`#featureBar .nav-feature .nav-item > a[data-id="${modelType}"]`);
    let $navItemLabel = $navItem.find('.label');
    if(!$navItemLabel.length) $navItemLabel = $('<span class="label size-sm canvas ring-0 rounded-md"></span>').appendTo($navItem);
    $navItemLabel.text(count);
}

/**
 * 初始化模型列表。
 * Initialize models list.
 *
 * @access public
 * @return void
 */
window.initModelList = async function()
{
    const isOK = await zui.AIPanel?.shared?.store.isOK();
    if(!isOK) return;

    $('#modelsList').addClass('loading');
    const dtable    = zui.DTable.query('#modelsList');
    const modelType = dtable.options.modelType || 'all';
    let models = await zui.AIPanel.shared.store.getLlmModels();
    models = models.reduce((acc, model)=>
    {
        if(!modelType || modelType === 'all' || model.abilities.includes(modelType)) acc.push(model);
        model.name = model.name || model.id;
        model.abilitiesText = (model.abilities || '').join(',');
        return acc;
    }, []);

    const langData      = dtable.options.langData;
    const canStartChat  = dtable.options.canStartChat;
    const cols = [
        {name: 'name', title: langData.model, sort: true, type: 'text'},
        {name: 'id', title: langData.modelID, type: 'category', sort: true, html: '<small class="font-mono text-gray">{0}</small>'},
        {name: 'abilities', title: langData.abilities, type: 'text', sort: true, onRenderCell(result, {col, row})
        {
            if (Array.isArray(row.data.abilities))
            {
                result[0] = zui.jsx`<div class="row flex-wrap gap-2">${row.data.abilities.map(x => zui.jsx`<span key=${x} class="label rounded size-sm gray-pale">${langData.abilityTypes[x] || x}</span>`)}</div>`;
            }
            return result;
        }},
        {name: 'actions', title: langData.actions, width: 90, type: 'actions', onRenderCell(result, {col, row})
        {
            if(!canStartChat) return result;
            let link          = $.createLink('aiapp', 'conversation', `chat=NEW&params=${btoa(JSON.stringify({model: row.data.id}))}`);
            let disabledClass = '';
            if(!row.data.abilities.includes('chat'))
            {
                link          = '';
                disabledClass = 'pointer-events-none disabled';
            }
            return [{html: `<a class="btn size-sm ghost text-primary ${disabledClass}" href="${link}">${langData.startChat}</a>`}];
        }},
    ];
    dtable.render(
    {
        sort: true,
        cols,
        data: models,
        emptyTip: langData.noDataTip,
        footer: function()
        {
            const rows = this.layout.allRows;
            return {html: langData.pageSummary.replace('%s', rows.length)};
        }
    });
    updateMenuLabel(modelType, models.length);
    $('#modelsList').removeClass('loading');
};

window.handleSearchModels = function(search)
{
    const dtable = zui.DTable.query('#modelsList');
    let data = dtable._allData;
    if(!data)
    {
        data = dtable.options.data;
        dtable._allData = data;
    }

    const searchKeys = zui.SearchMenu.Component.getSearchKeys(search);
    if(searchKeys.length)
    {
        data = data.filter(x => zui.SearchMenu.Component.isItemMatch(x, searchKeys, ['id', 'name', 'abilitiesText']));
    }
    else
    {
        data = dtable._allData;
    }

    dtable.render({data});
    updateMenuLabel(dtable.options.modelType, data.length);
};
