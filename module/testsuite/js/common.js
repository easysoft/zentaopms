$(function()
{
    if(window.flow != 'full')
    {
        $('.querybox-toggle').click(function()
        {
            $(this).parent().toggleClass('active');
        });
    }

    $('#suiteList tbody tr').each(function()
    {
        var $content = $(this).find('td.c-desc');
        var content  = $content.find('div').html();
        if(content.indexOf('<br') >= 0)
        {
            $content.append("<a href='###' class='more'><i class='icon icon-chevron-double-down'></i></a>");
        }
    });
})
$(document).on('click', 'td.c-desc .more', function(e)
{
    var $toggle = $(this);
    if($toggle.hasClass('open'))
    {
        $toggle.removeClass('open');
        $toggle.closest('.c-desc').find('div').css('height', '25px');
        $toggle.css('padding-top', 0);
        $toggle.find('i').removeClass('icon-chevron-double-up').addClass('icon-chevron-double-down');
    }
    else
    {
        $toggle.addClass('open');
        $toggle.closest('.c-desc').find('div').css('height', 'auto');
        $toggle.css('padding-top', ($toggle.closest('.c-desc').find('div').height() - $toggle.height()) / 2);
        $toggle.find('i').removeClass('icon-chevron-double-down').addClass('icon-chevron-double-up');
    }
});
