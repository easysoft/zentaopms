function switchVersion(e)
{
    const groupID = $(e.target).attr('data-group');
    const pivotID = $(e.target).attr('data-pivot');
    const version = $(e.target).attr('data-version');
    loadModal($.createLink('pivot', 'versions', `groupID=${groupID}&pivotID=${pivotID}&version=${version}`));
}
