window.setRelease = function(target)
{
    window.initAppPicker();

    const index     = $(target).closest('tr').data('index');
    const apps      = $(target).val();
    const $releases = $('#releases' + index).zui('picker');
    $releases.$.setValue('');

    const options = [{text: '', value: ''}];
    for(let id in releases)
    {
        if(releases[id].system == apps) options.push({value: id, text: releases[id].name, disabled: id == index});
    }

    $releases.render({items: options, required: rawMethod != 'edit'});
};

window.initAppPicker = function()
{
    let selected = [];
    let $appList = $('#systemForm').find('.picker-box [name^=apps]');
    $appList.each(function()
    {
        const $apps       = $(this);

        for(i = 0; i < $apps.length; i++)
        {
            let value = $apps.eq(i).val();
            if(value != '') selected.push(value);
        }
    });

    $appList.each(function()
    {
        const $apps       = $(this);
        const apps        = $apps.val();
        const $appsPicker = $apps.zui('picker');
        const appsItems   = $appsPicker.options.items;

        $.each(appsItems, function(i, item)
        {
            if(item.value == '') return;
            appsItems[i].disabled = selected.includes(item.value) && item.value != apps;
        })

        $appsPicker.render({items: appsItems, required: true});
    });
};

window.addItem = function(obj)
{
    let item         = $('#addItem > tbody').html().replace(/_i/g, itemIndex);
    const $currentTr = $(obj).closest('tr');
    $currentTr.after(item);

    setTimeout(window.initAppPicker, 100);

    $('#systemForm tbody tr .actions-list .btn-link').eq(1).removeClass('hidden');
    if(itemIndex >= appLength - 1) $('#systemForm tbody tr .actions-list .add-item').addClass('hidden');

    itemIndex ++;
};

window.deleteItem = function(obj)
{
    $(obj).closest('tr').remove();

    const trLen = $('#systemForm tbody tr').length;
    if(trLen < 2) $('#systemForm tbody tr .actions-list .btn-link').eq(1).addClass('hidden');
    if(trLen <= appLength) $('#systemForm tbody tr .actions-list .add-item').removeClass('hidden');

    setTimeout(window.initAppPicker, 100);
};

$(function()
{
    setTimeout(window.initAppPicker, 100);
});
