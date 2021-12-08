$(function()
{
    toggleAcl($('form input[name="acl"]:checked').val(), 'lib');
})

/**
 * Redirect the parent window.
 *
 * @param  int    hasLibPriv
 * @param  int    libID
 * @param  int    objectID
 * @access public
 * @return void
 */
function redirectParentWindow(hasLibPriv, libID, objectID)
{
    var link = hasLibPriv ? createLink('doc', 'tableContents', 'type=' + libType + '&objectID=' + objectID + '&libID=' + libID) : createLink('doc', 'tableContents', 'type=' + libType);
    parent.location.href = link;
}
