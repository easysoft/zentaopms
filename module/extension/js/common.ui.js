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

window.reloadPage = function()
{
    const modal = window.getCurrentModal();
    if(!modal) return;

    let link = $('#' + modal.id).attr('load-url');
    if(!link) return;
    modal.render({url: link});
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

window.disabledBtn = function(e)
{
    let $btn = $(e.target);
    if(!$btn.hasClass('primary')) $btn = $btn.closest('.primary');
    $btn.attr('disabled', 'disabled').addClass('disabled');
    loadModal($btn.attr('href'));
};
