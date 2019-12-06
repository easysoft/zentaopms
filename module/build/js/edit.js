var projectID = $('#project').val();
function loadProjects()
{
    var productID = $('#product').val();
    var branchID  = $('#branch').length > 0 ? $('#branch').val() : 0;
    $('#projectsBox').load(createLink('product', 'ajaxGetProjects', 'productID=' + productID + '&projectID=' + projectID + '&branch=' + branchID), function()
    {
        $('#projectsBox #project').chosen().removeAttr('onchange');
    });
}

$(document).on('change', '#product,#branch', function()
{
    loadProjects();
})
