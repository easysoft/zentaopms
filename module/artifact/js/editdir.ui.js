window.parentPickerRequestToken = 0;

window.loadParents = function(retries)
{
    retries = retries === undefined ? 10 : retries;

    const $artifactField  = $('[name=artifactID]');
    const $artifactPicker = $artifactField.zui('picker');
    const artifactID      = $artifactPicker && $artifactPicker.$ ? $artifactPicker.$.state.value : $artifactField.val();

    if(!artifactID)
    {
        if(retries > 0) setTimeout(function() { window.loadParents(retries - 1); }, 100);
        return;
    }

    const $parentField      = $('[name=parent]');
    const $parentPicker     = $parentField.zui('picker');
    const $parentLoadingDom = $parentField.closest('.form-group').find('.picker-box').first();
    const currentArtifact   = String(window.currentArtifactID || '');
    const selectedArtifact  = String(artifactID);
    const currentPath       = selectedArtifact === currentArtifact ? (window.currentPathEncoded || '') : '';
    const defaultParentPath = selectedArtifact === currentArtifact ? (window.currentParentPath || '/') : '/';
    const link              = $.createLink('artifact', 'ajaxGetDirParentItems', 'artifactID=' + artifactID + '&path=' + currentPath);
    const requestToken      = ++window.parentPickerRequestToken;

    if($parentPicker) $parentPicker.render({disabled: true});
    if($parentLoadingDom.length) toggleLoading($parentLoadingDom, true);

    $.getJSON(link)
        .done(function(items)
        {
            if(requestToken !== window.parentPickerRequestToken || !$parentPicker) return;

            $parentPicker.render({items: items, disabled: false});
            $parentPicker.$.setValue(defaultParentPath);
        })
        .always(function()
        {
            if(requestToken !== window.parentPickerRequestToken) return;

            if($parentPicker) $parentPicker.render({disabled: false});
            if($parentLoadingDom.length) toggleLoading($parentLoadingDom, false);
        });
};
