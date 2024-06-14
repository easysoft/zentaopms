window.waitDom('body.body-modal .toolbar', function()
{
    $('.body-modal .toolbar a[data-load="modal"]').attr('data-toggle', 'modal').removeAttr('data-load');
})
