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
    const models = await zui.AIPanel.shared.store.getLlmModels();
    (models || []).forEach((model, index)=>
    {
        model.index = index + 1;
        model.name  = model.name || model.id;
    });

    const dtable        = zui.DTable.query('#modelsList');
    const langData      = dtable.options.langData;
    const canStartChat  = dtable.options.canStartChat;
    const abilityColors = {chat: 'primary', 'function-calling': 'success', reasoning: 'warning', embedding: 'danger'};
    const cols = [
        {name: 'name', title: langData.model, sortType: true, type: 'text'},
        {name: 'id', title: langData.modelID, type: 'category', sortType: true, html: '<small class="font-mono text-gray">{0}</small>'},
        {name: 'abilities', title: langData.abilities, type: 'text', sortType: true, onRenderCell(result, {col, row})
        {
            if (Array.isArray(row.data.abilities))
            {
                result[0] = zui.jsx`<div class="row flex-wrap gap-2">${row.data.abilities.map(x => zui.jsx`<span key=${x} class=${`label rounded size-sm ${abilityColors[x] || 'gray'}-pale`}>${langData.abilityTypes[x] || x}</span>`)}</div>`;
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
    dtable.render({
        cols,
        data: models,
        footer: function()
        {
            const rows = this.layout.allRows;
            return {html: langData.pageSummary.replace('%s', rows.length)};
        }
    });
    $('#modelsList').removeClass('loading');
};
