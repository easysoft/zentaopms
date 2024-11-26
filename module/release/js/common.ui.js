window.loadBuilds = function(productID)
{
    if(!productID) return;

    const systemID = $('[name=system]').val();
    if(!systemID) return;

    let options = [];
    $.getJSON($.createLink('build', 'ajaxGetSystemBuilds', `productID=${productID}&systemID=${systemID}`), function(data)
    {
        options = data;
    });

    const $buildPicker = $('select[name^=build]').zui('picker');
    $buildPicker.render({items: options, multiple: true});
    $buildPicker.$.setValue('');
}
