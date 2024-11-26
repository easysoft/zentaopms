window.loadBuilds = function(productID)
{
    if(!productID) return;

    const systemID = $('[name=system]').val();
    if(!systemID) return;

    $.getJSON($.createLink('build', 'ajaxGetSystemBuilds', `productID=${productID}&systemID=${systemID}`), function(data)
    {
        $('select[name^=build]').zui('picker').render({items: data, multiple: true});
    });
}
