window.changeScope = function(e)
{
    const scope = e.target.value;
    loadModal($.createLink('doc', 'addTemplateType', `scope=${scope}`));
}
