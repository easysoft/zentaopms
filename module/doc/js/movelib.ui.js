window.changeSpace = function(e)
{
    let selectedSpace = $(e.target).val();
    loadModal($.createLink('doc', 'moveLib', "libID=" + libID + "&targetSpace=" + selectedSpace));
};

window.toggleLibAcl = function(e)
{
    $this = $(e.target);
    $this.closest('form').find('#whiteListBox').toggleClass('hidden', $(e.target).val() != 'private');
};

window.clickSubmit = function(e)
{
    const formUrl  = $('form').attr('action');
    const formData = new FormData($("form")[0]);
    const target   = $('[name="space"]').val();

    if(target != 'mine')
    {
        $.ajaxSubmit({url: formUrl, data: formData});
        return;
    }

    zui.Modal.confirm({message: errorOthersCreated}).then((res) =>
    {
        if(res)
        {
            $.ajaxSubmit({url: formUrl, data: formData});
        }
    });

    return false;
}
