/**
 * Ajax load unlinked builds with project and product.
 *
 * @access public
 * @return void
 */
function loadBuilds()
{
    var productID = $('#product').val();
    $('#buildBox').load(createLink('projectrelease', 'ajaxLoadBuilds', "projectID=" + projectID + "&productID=" + productID), function()
    {
        $('#build').attr('data-placeholder', multipleSelect).chosen();
    });
}
