window.toggleImportObjectBox = function(e)
{
    let isImport = $(e.target).val() == 'on';
    if(!isImport)
    {
        $("input[name^='importObjectList']").attr('disabled', 'disabled');
        $('#objectBox').hide();
    }
    else
    {
        $("input[name^='importObjectList']").removeAttr('disabled');
        $('#objectBox').show();
    }
}
