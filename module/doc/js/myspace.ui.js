window.onSortEnd = function(from, to, type)
{
    if(!from || !to) return false;

    const url  = $.createLink('doc', 'sortDoc');
    const form = new FormData();
    form.append('orders', JSON.stringify(this.state.rowOrders));
    $.ajaxSubmit({url, data: form});
    return true;
}
