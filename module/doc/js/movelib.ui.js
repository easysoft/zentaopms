window.changeSpace = function(e)
{
    let selectedSpace = $(e.target).val();
    if((selectedSpace == 'mine' && targetSpace != 'mine') || (selectedSpace != 'mine' && targetSpace == 'mine'))
    {
        loadModal($.createLink('doc', 'moveLib', "libID=" + libID + "&targetSpace=" + selectedSpace));
    }
};

window.toggleLibAcl = function(e)
{
    $this = $(e.target);
    $this.closest('form').find('#whiteListBox').toggleClass('hidden', $(e.target).val() != 'private');
};
