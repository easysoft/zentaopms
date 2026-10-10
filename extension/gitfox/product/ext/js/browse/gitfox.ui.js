window.searchProduct = function()
{
    const keyword = $('#productKeyword').val().trim();
    $.cookie.set('gitfoxProductKeyword', keyword, {expires: config.cookieLife, path: config.webRoot});

    const searchUrl = productSearchLink;
    if(typeof loadPage === 'function') loadPage(searchUrl);
    else location.href = searchUrl;
};

$(document).on('click', '#productSearchBtn', window.searchProduct);
$(document).on('keydown', '#productKeyword', function(e)
{
    if(e.keyCode == 13)
    {
        e.preventDefault();
        window.searchProduct();
    }
});
