$(function()
{
    $("#" + status + "Tab").addClass('btn-active-text');
    $('.table-footer .check-all').on('click', function()
    {
        if($(this).hasClass('checked'))
        {
            $(this).removeClass('checked');
            $('.dtable-row').removeClass('is-checked');
            $('.has-checkbox input[type="checkbox"]').prop('checked', false);
        }
        else
        {
            $(this).addClass('checked');
            $('.dtable-row').addClass('is-checked');
            $('.has-checkbox input[type="checkbox"]').prop('checked', true);
        }
    })
});

/**
 * Location to product list.
 *
 * @param  int    productID
 * @param  int    projectID
 * @param  string status
 * @access public
 * @return void
 */
function byProduct(productID, projectID, status)
{
    location.href = createLink('project', 'all', "status=" + status + "&project=" + projectID + "&orderBy=" + orderBy + '&productID=' + productID);
}
