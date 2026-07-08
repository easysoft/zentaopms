/**
 * 导入流水线表单前端交互。
 * Handle import form interactions.
 */
window.loadPipelineName = function()
{
    const $pipelinePicker = $('[name=pipeline]').zui('picker');
    const $nameInput      = $('[name=name]');
    if(!$pipelinePicker || !$pipelinePicker.$) return;

    const selections  = $pipelinePicker.$.state.selections || [];
    const pipelineName = selections.length > 0 ? selections[0].text : '';

    if(pipelineName && (!$nameInput.val() || $nameInput.data('auto'))) $nameInput.val(pipelineName).data('auto', true);
};

$(function()
{
    loadPipelineName();
});
