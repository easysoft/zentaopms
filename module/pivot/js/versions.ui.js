function previewVersion(e)
{
    const groupID = $(e.target).attr('data-group');
    const pivotID = $(e.target).attr('data-pivot');
    const version = $(e.target).attr('data-version');
    loadModal($.createLink('pivot', 'versions', `groupID=${groupID}&pivotID=${pivotID}&version=${version}`));
}

function loadCustomPivot()
{
    const filters = getFilterValues();
    const keys = Object.keys(filters).slice(-3);
    const filterValues = keys.reduce((acc, key, index) => {
        acc[index] = filters[key];
        return acc;
    }, {});
    const form = zui.createFormData({filterValues});
    form.append('preview', '1');

    const link = $.createLink('pivot', 'versions', 'groupID=' + group + '&pivotID=' + pivot + '&version=' + version);
    loadTarget(link, '#pivotVersionPanel', {method: 'POST', data: form});
}
