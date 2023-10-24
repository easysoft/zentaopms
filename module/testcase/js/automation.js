function loadNodes()
{
    var nodeLink = createLink('zanode', 'ajaxGetNodes');
    $('#nodeIdBox').load(nodeLink, function()
    {
        $('#nodeIdBox').find('#node').picker();
    });
}
