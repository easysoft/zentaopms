window.locateLogin = function(obj)
{
    let $this = $(obj);
    $.getJSON($this.data('url'), function(data)
    {
        if(typeof data.load != 'undefined') top.location.href = data.load;
    })
}
