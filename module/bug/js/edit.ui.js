$(function()
{
    changeProductConfirmed = false;
    resolution             = $('[name=resolution]').val();
});

function changeResolvedBuild(event)
{
    const resolution = $('[name=resolution]').val();
    if(resolution != 'fixed') return false;

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
    const resolution = $(event.target).val();
    if(resolution == 'duplicate')
    {
        $('[name=duplicateBug]').closest('tr').removeClass('hidden');
    }
    else
    {
        $('[name=duplicateBug]').closest('tr').addClass('hidden');
    }
}

function linkBug(event)
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
