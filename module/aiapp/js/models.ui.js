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
    models?.forEach((model, index)=> {
        model.index = index + 1;
    });

    const {modelLang, actionLang, converseLang} = $('.models-view').data();
    const cols = [
        {name: 'index', title: 'ID', type: 'id', sortType: false},
        {name: 'id', title: modelLang},
        {name: 'actions', title: actionLang, width: 90, type: 'actions', actions: ['converse'], actionsMap: {
            // TODO: The conversation page has not been developed yet.
            converse: {text: converseLang},
        }},
    ];
    $('#modelsList').zui('dtable').render({cols, data: models});
    $('#modelsList').removeClass('loading');
}
