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

        const values = [];
        data.forEach(function(item)
        {
            if(`,${releaseBuilds}`.includes(item.value)) values.push(item.value);
        });
        $buildPicker.$.setValue(values);
    });
}

window.changeStatus = function()
{
    const status = $('[name=status]').val();
    if(status == 'wait')
    {
        $('#releasedDate').closest('.form-row').addClass('hidden');
        $('[data-name=date] .form-label').addClass('required');
    }
    else
    {
        $('#releasedDate').closest('.form-row').removeClass('hidden');
        $('[data-name=date] .form-label').removeClass('required');
    }
}
