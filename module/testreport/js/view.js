$(function()
{
    $('.tab-pane').removeClass('active');
    tab = tab == 'basic' ? 'basic' : 'tabCase';
    $('.tab-pane#' + tab).addClass('active');

    $('.nav.nav-tabs a[data-toggle="tab"]').on('shown.zui.tab', function(e)
    {
        var href = $(e.target).data('target');
        if(href == '#tabComment')
        {
            $('.tab-content #tabComment .article-content img').each(function(){setImageSize($(this), 0 , 0);});
        }
    });
});
