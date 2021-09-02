/**
 * Redirect the parent window.
 *
 * @param  string objectType
 * @access public
 * @return void
 */
function redirectParentWindow(objectType)
{
    config.onlybody = 'no';
    var link = createLink('doc', 'create', 'objectType=' + objectType + '&objectID=0&libID=0') + '#app=doc';
    window.parent.$.apps.open(link);
}
