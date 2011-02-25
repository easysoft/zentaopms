/* Load the products of the roject. */
function loadProducts(project)
{
    link = createLink('project', 'ajaxGetProducts', 'projectID=' + project);
    $('#productBox').load(link);
}

/* Set doc type. */
function setType(type)
{
    if(type == 'url')
    {
        $('#urlBox').show();
        $('#fileBox').hide();
        $('#contentBox').hide();
    }
    else if(type == 'text')
    {
        $('#urlBox').hide();
        $('#fileBox').hide();
        $('#contentBox').show();
    }
    else
    {
        $('#urlBox').hide();
        $('#fileBox').show();
        $('#contentBox').hide();
    }
}
