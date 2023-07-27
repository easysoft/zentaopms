window.loadInModal = function(link)
{
    target = zui.Modal.query().id;
    target = `#${target}`;

    const modal = zui.Modal.query(target);
    if(!modal) return;

    modal.render({url: link});
}
