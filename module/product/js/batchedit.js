$(function()
{
    $("input[type='radio'][value='open']").parent().each(function()
    {
        this.title = openTip;
    });

    $("input[type='radio'][value='private']").parent().each(function()
    {
        this.title = privateTip;
    });
})

/**
 * Load product lines by program.
 *
 * @param  int $programID
 * @param  int $productID
 * @access public
 * @return void
 */
function loadProductLines(programID, productID)
{
    var link = createLink('product', 'ajaxGetLine', 'programID=' + programID + '&productID=' + productID);
    $('#line_' + productID).load(link, function()
    {
        $('#lines' + productID).picker();
    });
}
