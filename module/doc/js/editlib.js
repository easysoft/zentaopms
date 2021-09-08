$(function()
{
    toggleAcl($('form input[name="acl"]:checked').val(), 'lib');
})

/**
 * Redirect the parent window.
 *
 * @param  int    hasLibPriv
 * @param  int    libID
 * @access public
 * @return void
 */
function redirectParentWindow(hasLibPriv, libID)
{
    var link = hasLibPriv ? createLink('doc', 'tableContents', 'type=' + libType + '&objectID=0&libID=' + libID) : createLink('doc', 'tableContents', 'type=' + libType);
    parent.location.href = link;
}
