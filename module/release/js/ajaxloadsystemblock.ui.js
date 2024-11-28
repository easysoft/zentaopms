window.setRelease = function(e, releaseID)
{
    window.initAppPicker();

    const apps      = $(e.target).val();
    const $releases = $('#releases' + releaseID).zui('picker');
    $releases.$.setValue('');

    const options = [];
    for(let id in releases)
    {
        if(releases[id].system == apps) options.push({value: id, text: releases[id].name, disabled: id == releaseID});
    }

    $releases.render({items: options, required: true});
};

window.initAppPicker = function()
{
    let selected = [];
    let $appList = $('#systemForm').find('.picker-box [name^=apps]');
    $appList.each(function()
    {
        const $apps       = $(this);
        const apps        = $apps.val();
        const $appsPicker = $apps.zui('picker');
        const appsItems   = $appsPicker.options.items;

        for(i = 0; i < $apps.length; i++)
        {
            let value = $apps.eq(i).val();
            if(value != '') selected.push(value);
        }

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

    itemIndex ++;
};

window.deleteItem = function(obj)
{
    $(obj).closest('tr').remove();
    if($('#systemForm tbody tr').length < 2) $('#systemForm tbody tr .actions-list .btn-link').eq(1).addClass('hidden');

    setTimeout(window.initAppPicker, 100);
};
