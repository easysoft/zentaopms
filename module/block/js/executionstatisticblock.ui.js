$(function()
{
    initNavTabs();
});

function initNavTabs()
{
    $('.executionstatistic-block.block-sm .nav.nav-tabs').find('.nav-item.nav-switch').removeClass('active');
    $('.executionstatistic-block.block-sm .nav.nav-tabs').find('.nav-item.nav-switch').each(function()
    {
        if($(this).find('a[data-toggle=tab]').hasClass('active')) $(this).addClass('active');
    })
}

function switchNav(event)
{
    var isPrev   = $(event.target).closest('.nav-item').hasClass('nav-prev');
    var $navTabs = $(event.target).closest('.nav-tabs');

    var $switch = isPrev ? $navTabs.find('.nav-switch > a.active').parent().prev() : $navTabs.find('.nav-switch > a.active').parent().next();
    if($switch.length) $switch.find('a[data-toggle=tab]').trigger('click');
}

$('.executionstatistic-block.block-sm .nav.nav-tabs').on('show', function(event, info)
{
    $(event.target).find('.nav-item.nav-switch').removeClass('active');
    $(event.target).find('.nav-item.nav-switch').each(function()
    {
        if($(this).find('a[data-toggle=tab]').hasClass('active')) $(this).addClass('active');
    })
});

$('.executionstatistic-block .nav.nav-tabs').on('show', function(event, info)
{
    $('#' + info[1] + ' .chart').each(function()
    {
        $(this).find('div').data('zui.ECharts').chart.resize();
    });
});
