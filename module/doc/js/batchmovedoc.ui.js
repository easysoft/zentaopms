window.changeSpace = function(e)
{
    let selectedSpace = e.target.value;
    const [spaceType, selectedSpaceID] = selectedSpace.split('.');
    loadModal($.createLink('doc', 'batchMoveDoc', "type=" + spaceType + "&encodeDocIdList=" + encodeDocIdList + "&spaceID=" + selectedSpaceID + "&libID=0&moduleID=0"));
};
window.changeLib = function(e)
{
    let selectedLib = e.target.value;
    loadModal($.createLink('doc', 'batchMoveDoc', "type=" + type + "&encodeDocIdList=" + encodeDocIdList + "&spaceID=" + spaceID + "&libID=" + selectedLib + "&moduleID=0"));
};

window.toggleDocAcl = function(e)
{
    $this = $(e.target);
    $this.closest('form').find('#whiteListBox').toggleClass('hidden', $(e.target).val() != 'private' || type == 'mine');
};
