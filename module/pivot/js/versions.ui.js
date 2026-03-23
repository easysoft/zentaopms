function previewVersion(e)
{
    const groupID = $(e.target).attr('data-group');
    const pivotID = $(e.target).attr('data-pivot');
    const version = $(e.target).attr('data-version');
    loadModal($.createLink('pivot', 'versions', `groupID=${groupID}&pivotID=${pivotID}&version=${version}`));
}

function loadCustomPivot()
{
    const filterValues = getFilterValues('versions');
    const form = zui.createFormData();
    form.append('preview', '1');
    form.append('filterValues', filterValues);

    const link = $.createLink('pivot', 'versions', 'groupID=' + group + '&pivotID=' + pivot + '&version=' + version);
    loadTarget(link, '#pivotVersionPanel', {method: 'POST', data: form});
}
