/**
 * Set card count.
 *
 * @param  string $heightType
 * @access public
 * @return void
 */
function setCardCount(heightType)
{
    heightType != 'custom' ? $('#cardBox').addClass('hidden') : $('#cardBox').removeClass('hidden');
}

$(function()
{
    var heightType = $("[name='heightType']:checked").val();
    setCardCount(heightType);
})
