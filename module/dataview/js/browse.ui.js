$(document).ready(function()
{
    $('.nav-item > a[data-toggle=tab]').on('click', function()
    {
        const tab  = $(this).attr('href');
        const tree = $(tab + ' >  #moduleMenu > .tree').zui();
        const newItems = JSON.parse(tab == '#view' ? groupTree : originTable);
        tree.render({items: newItems, show: false});
    });

    $(".nav-item > a[data-toggle='tab'][href='#" + type + "']")[0].click();
})
