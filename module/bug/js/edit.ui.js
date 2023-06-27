$(function()
{
    changeProductConfirmed = false;
    resolution             = $('#resolution').val();
});

function changeResolvedBuild(event)
{
    const resolution = $('#resolution').val();
    if(resolution != 'fixed') return false;

    if($('#resolvedBuild').val() != bug.resolvedBuild)
    {
        zui.Modal.confirm({message: confirmUnlinkBuild, onResult: function(result)
        {
            if(!result) $('#resolvedBuild').val(bug.resolvedBuild);
        }});
    }
}

function changeResolution(event)
{
    const resolution = $(event.target).val();
    if(resolution == 'duplicate')
    {
        $('#duplicateBugBox').show();
    }
    else
    {
        $('#duplicateBugBox').hide();
    }
}

function linkBug(event)
{
    const relatedBugs = $('#relatedBug').val();
    const link        = $.createLink('bug', 'linkBugs', 'bugID=' + bug.id + '&browseType=&excludeBugs=' + relatedBugs, '', true);

    openUrl(link, {load: 'modal'});
}
