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
}
