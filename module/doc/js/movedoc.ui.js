window.changeSpace = function(e)
{
    let selectedSpace = e.target.value;
    const [spaceType, spaceID] = selectedSpace.split('.');
    loadModal($.createLink('doc', 'moveDoc', "docID=" + docID + "&libID=0&spaceType=" + spaceType + "&space=" + spaceID));
};
window.changeLib = function(e)
{
    let selectedLib = e.target.value;
    loadModal($.createLink('doc', 'moveDoc', "docID=" + docID + "&libID=" + selectedLib + "&spaceType=" + spaceType + "&space=" + space));
};

window.toggleDocAcl = function(e)
{
    $this = $(e.target);
    $this.closest('form').find('#whiteListBox').toggleClass('hidden', $(e.target).val() != 'private');
};
