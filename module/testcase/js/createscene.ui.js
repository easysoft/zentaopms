function loadProductRelated()
{
    const productID = $(event.target).val();
    loadProductBranches(productID);
    loadProductModules(productID);
    loadScenes(productID, 'parent');
}

function loadBranchRelated()
{
    const productID = $('[name=product]').val();
    loadProductModules(productID);
}

function loadModuleRelated()
{
    const productID = $('[name=product]').val();
    loadScenes(productID, 'parent');
}

function refreshModules()
{
    const productID = $('[name=product]').val();
    loadProductModules(productID);
}

window.renderModulePicker = function()
{
    refreshModules();
    $('#module').parent().find('.input-group-btn').hide();
}
