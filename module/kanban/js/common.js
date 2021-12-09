$(function()
{
    if(typeof acl != 'undefined' && acl != null) setWhite(acl);
});
/**
 * Set white list.
 *
 * @param  string $acl
 * @access public
 * @return void
 */
function setWhite(acl)
{
    acl != 'open' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

/**
 * Set mailto.
 *
 * @param  string $field
 * @param  int    $value
 * @access public
 * @return void
 */
function setMailto(field, value)
{
    var link = createLink('kanban', 'ajaxGetContactUsers', "listID=" + value);
    $.post(link, function(data)
    {
        $('#team').replaceWith(data);
        $('#team_chosen').remove();
        $('#team').chosen();
    })
}
