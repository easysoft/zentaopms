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
    const $this = $(e instanceof Event ? e.target : e);
    const $form = $this.closest('form,.modal-body').first();
    const aclType = $form.find('[name="acl"]:checked').val();
    $form.find('#whiteListBox').toggleClass('hidden', type === 'mine' || aclType !== 'private');
};
