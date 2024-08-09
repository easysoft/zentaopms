window.changeSpace = function(e)
{
    let targetSpace = e.target.value;
    if((targetSpace == 'mine' && space != 'mine') || (targetSpace != 'mine' && space == 'mine'))
    {
        loadModal($.createLink('doc', 'editLib', "libID=" + doclibID + "&targetSpace=" + targetSpace));
    }
};

$(function()
{
    toggleAcl('lib');
})
