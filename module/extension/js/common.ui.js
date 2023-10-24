window.loadUrl = function($obj)
{
    let link = $($obj).attr('load-url');
    window.loadInModal(link);
}

window.loadParentUrl = function($obj)
{
    let link = $($obj).attr('load-url');
    window.hideModal();
    loadPage(link);
}

window.getCurrentModal = function()
{
    target = zui.Modal.query().id;
    target = `#${target}`;

    return zui.Modal.query(target);
}

window.loadInModal = function(link)
{
    const modal = window.getCurrentModal();
    if(!modal) return;

    $("#" + modal.id).attr('load-url', link);
    modal.render({url: link});
}

window.hideModal = function()
{
    const modal = window.getCurrentModal();
    if(!modal) return;

    modal.hide();
}
