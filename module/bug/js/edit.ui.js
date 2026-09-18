$(function()
{
    changeProductConfirmed = false;
    resolution             = $('[name=resolution]').val();
});

function changeResolvedBuild(event)
{
    if($(event.target).val() != bug.resolvedBuild && bug.resolvedBuild != '')
    {
        zui.Modal.confirm({message: confirmUnlinkBuild, onResult: function(result)
        {
            if(!result) $(event.target).val(bug.resolvedBuild);
        }});
    }
}

function changeResolution(event)
{
    const $target = $(event.target);
    const resolution = $target.val();
    $target.closest('form').find('.form-row[data-name="duplicateBug"]').toggleClass('hidden', resolution != 'duplicate');
}

function linkBug()
{
    const relatedBugs = $('[name^=relatedBug]').val();
    var   linkedBugs  = '';
    $.each(relatedBugs, function(index, bug)
    {
        linkedBugs += ',' + bug;
    });
    const link = $.createLink('bug', 'linkBugs', 'bugID=' + bug.id + '&browseType=false&excludeBugs=' + linkedBugs, '', true);

    openUrl(link, {load: 'modal', size: 'lg'});
}
