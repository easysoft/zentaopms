$('.ajaxCollect').click(function (event) {
    var obj = $(this);
    var url = obj.data('url');
    $.get(url, function(response)
    {
        if(response.status == 'yes')
        {
            obj.children('img').attr('src', 'static/svg/star.svg');
        }
        else
        {
            obj.children('img').attr('src', 'static/svg/star-empty.svg');
        }
    }, 'json');
    return false;
});
