function switchNav(event)
{
    var $nav = $('productstatistic-block nav-tabs');
    var isPrev = $(event.target).parent().hasClass('nav-prev');

    var $switch = isPrev ? $('.nav-switch.active').prev() : $('.nav-switch.active').next();
    if($switch.length) $switch.find('a[data-toggle=tab]').trigger('click');
}
