/**
 * Redirect the parent window.
 *
 * @param  string objectType
 * @param  int    libID
 * @param  string docType
 * @access public
 * @return void
 */
function redirectParentWindow(objectType, libID, moduleID, docType)
{
    config.onlybody = 'no';

    var link = '';
    if(docType == 'api')
    {
        link = createLink('api', 'create', 'libID=' + libID + '&moduleID=' + moduleID + '&space=' + objectType);
    }
    else
    {
        link = createLink('doc', 'create', 'objectType=' + objectType + '&objectID=0&libID=' + libID + '&moduleID=' + moduleID + '&docType=' + docType) + '#app=doc';
    }
    window.parent.$.apps.open(link);
}

/**
 * Load doc libs by type.
 *
 * @param  string  type
 * @return void
 */
function loadDocLibs(space, type)
{
    var link = createLink('doc', 'ajaxGetLibsByType', "space=" + space + "&type=" + type);
    $('#moduleBox').load(link, function(){$('#moduleBox').find('select').picker(); $('#moduleLabel').remove();});
}
