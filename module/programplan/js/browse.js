$(function()
{
    setTimeout(function()
    {
        fixScroll();
    }, 500);
})

function fixScroll()
{
    var $scrollwrapper = $('div.datatable').first().find('.scroll-wrapper:first');
    if($scrollwrapper.size() == 0)return;

    var $tfoot       = $('div.datatable').first().find('table tfoot:last');
    var scrollOffset = $scrollwrapper.offset().top + $scrollwrapper.find('.scroll-slide').height();
    if($tfoot.size() > 0) scrollOffset += $tfoot.height();
    if($('div.datatable.head-fixed').size() == 0) scrollOffset -= '29';
    var windowH = $(window).height();
    if(scrollOffset > windowH + $(window).scrollTop()) $scrollwrapper.css({'position': 'fixed', 'bottom': 50 + 'px'});
    $(window).scroll(function()
    {
          newBottom = $tfoot.hasClass('fixedTfootAction') ? 50 + $tfoot.height() : 50;
          if(typeof(ssoRedirect) != "undefined") newBottom = 50;
          if(scrollOffset <= windowH + $(window).scrollTop())
          {
              $scrollwrapper.css({'position':'relative', 'bottom': '0px'});
          }
          else if($scrollwrapper.css('position') != 'fixed')
          {
              $scrollwrapper.css({'position': 'fixed', 'bottom': newBottom + 'px'});
              bottom = newBottom;
          }
    });
}
