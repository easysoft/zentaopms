window.locateLogin = function(obj)
{
    let $this = $(obj);
    $.getJSON($this.data('url'), function(data)
    {
        if(typeof data.load != 'undefined') location.href = data.load;
    })
}

window.locatePage = function(obj)
{
    location.href = $(obj).data('url');
}
