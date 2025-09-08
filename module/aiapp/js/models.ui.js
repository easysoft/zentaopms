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
}
