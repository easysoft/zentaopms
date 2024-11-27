window.loadBuilds = function(productID)
{
    if(!productID) return;

    const systemID = $('[name=system]').val();
    if(!systemID) return;

    const $buildPicker = $('select[name^=build]').zui('picker');
    $buildPicker.render({items: [], multiple: true});
    $buildPicker.$.setValue('');

    $.getJSON($.createLink('build', 'ajaxGetSystemBuilds', `productID=${productID}&systemID=${systemID}`), function(data)
    {
        $buildPicker.render({items: data, multiple: true});
    });
}
