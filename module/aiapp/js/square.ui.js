if(!window.aiSquare) window.aiSquare = {};

window.aiSquare.handleStarBtnClick = function(e)
{
    e.preventDefault();
    e.stopPropagation();

    const $btn = $(this);
    const url = $btn.attr('data-url');
    $.get(url, function(response)
    {
        if(response.status == '1')
        {
            $btn.children('img').attr('src', 'static/svg/star.svg');
            $btn.attr('data-url', $btn.attr('data-url').replace('false', 'true'));
        }
        else
        {
            $btn.children('img').attr('src', 'static/svg/star-empty.svg');
            $btn.attr('data-url', $btn.attr('data-url').replace('true', 'false'));
        }
        loadCurrentPage({selector : '#mainMenu'});
    }, 'json');
};
