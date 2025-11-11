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

    const {modelLang, actionLang, converseLang, canConverse} = $('.models-view').data();
    const cols = [
        {name: 'index', title: 'ID', type: 'id', sortType: false},
        {name: 'name', title: modelLang},
        {name: 'actions', title: actionLang, width: 90, type: 'actions', onRenderCell(_result, {col, row})
        {
            if(!canConverse) return [{html: ''}];

            let link          = $.createLink('aiapp', 'conversation', `chat=NEW&params=${btoa(JSON.stringify({model: row.data.id}))}`);
            let disabledClass = '';
            if(!row.data.abilities.includes('chat'))
            {
                link          = '';
                disabledClass = 'pointer-events-none disabled';
            }
            return [{html: `<a class="btn size-sm ghost text-primary ${disabledClass}" href="${link}">${converseLang}</a>`}];
        }},
    ];
    $('#modelsList').zui('dtable').render({cols, data: models});
    $('#modelsList').removeClass('loading');
}

/**
 * 为模型列表设置表格页脚。
 * Set models summary for table footer.
 *
 * @access public
 * @return object
 */
window.setModelsStatistics = function()
{
    const pageSummary = $('.models-view').data('pageSummary');
    const rows        = this.layout.allRows;
    return {html: pageSummary.replace('%s', rows.length)};
}
