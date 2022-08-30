$(function()
{
    loadDocLibs(defaultType);
})

/**
 * Redirect the parent window.
 *
 * @param  string objectType
 * @param  int    libID
 * @param  string docType
 * @access public
 * @return void
 */
function redirectParentWindow(objectType, libID, docType)
{
    config.onlybody = 'no';
    if(objectType == 'api')
    {
        var link = createLink('api', 'create', 'libID=' + libID) + '#app=doc';
    }
    else
    {
        var link = createLink('doc', 'create', 'objectType=' + objectType + '&objectID=0&libID=' + libID + '&moduleID=0&docType=' + docType + '&fromGlobal=true') + '#app=doc';
    }
    window.parent.$.apps.open(link);
}

/**
 * Load doc libs by type.
 *
 * @param  string  type
 * @return void
 */
function loadDocLibs(type)
{
    $.get(createLink('doc', 'ajaxGetLibsByType', "type=" + type), function(data)
    {
        $('#lib').replaceWith(data);
        $('#lib_chosen').remove();
        $('#lib').chosen();

        if($('#lib').find('option').length == 0)
        {
            $('#submit').attr('disabled', 'disabled');
        }
        else
        {
            $('#submit').removeAttr('disabled');
        }
    })

    $('#docType').toggleClass('hidden', type == 'api');
}
