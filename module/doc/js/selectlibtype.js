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
    if(objectType == 'api')
    {
        var link = createLink('api', 'create', 'libID=0') + '#app=doc';
    }
    else
    {
        var link = createLink('doc', 'create', 'objectType=' + objectType + '&objectID=0&libID=0') + '#app=doc';
    }
    window.parent.$.apps.open(link);
}
