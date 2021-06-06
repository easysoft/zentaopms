$('.ajaxCollect').click(function (event) {
    var obj = $(this);
    var url = obj.data('url');
    $.get(url, function(response)
    {
        if(response.status == 'yes')
        {
            obj.children('i').removeClass().addClass('icon icon-star text-yellow');
            obj.parent().prev().children('.file-name').children('i').remove('.icon');
            obj.parent().prev().children('.file-name').prepend('<i class="icon icon-star text-yellow"></i> ');
        }
        else
        {
            obj.children('i').removeClass().addClass('icon icon-star-empty');
            obj.parent().prev().children('.file-name').children('i').remove(".icon");
        }
    }, 'json');
    return false;
});
