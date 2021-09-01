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
    var link = hasLibPriv ? createLink('doc', 'tableContents', 'type=book&objectID=0&libID=' + libID) : createLink('doc', 'tableContents', 'type=book');
    parent.location.href = link;
}
