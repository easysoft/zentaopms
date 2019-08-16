$(function()
{
    $('.tab-pane').removeClass('active');
    $('.tab-pane:first').addClass('active');
    $('.nav.nav-tabs a[data-toggle="tab"]').on('shown.zui.tab', function(e)
    {
        var href = $(e.target).data('target');
        if(href == '#tabComment')
        {
            $('.tab-content #tabComment .article-content img').each(function(){setImageSize($(this), 0 , 0);});
        }
    });
});
