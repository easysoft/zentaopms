if(!window.aiSquare) window.aiSquare = {};

window.aiSquare.handleStarBtnClick = function()
{
    const $btn = $(this);
    const url = $btn.data('url');
    $.get(url, function(response)
    {
        if(response.status == '1')
        {
            $btn.children('img').attr('src', 'static/svg/star.svg');
            $btn.data('url', $btn.data('url').replace('false', 'true'));
        }
        else
        {
            $btn.children('img').attr('src', 'static/svg/star-empty.svg');
            $btn.data('url', $btn.data('url').replace('true', 'false'));
        }
    }, 'json');
};
