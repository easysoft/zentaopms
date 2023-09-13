$(function()
{
    const $searchQuery = $('#globalSearchInput');
    const setSelected  = function()
    {
        $searchQuery.data('selectedKey', getSearchType());
    };

    $searchQuery.on('change keyup paste input propertychange', setSelected).on('focus', function()
    {
        setTimeout(setSelected, 300);
    });
});

/**
 * Get the search Type according to the current module and the current method.
 *
 * @access public
 * @return string
 */
function getSearchType()
{
    if(vision == 'lite') return 'story';

    const appInfo = $.apps.getLastApp();
    const appPageModuleName = appInfo.iframe.contentWindow.config.currentModule;
    const appPageMethodName = appInfo.iframe.contentWindow.config.currentMethod;

    if(appPageModuleName == 'product' && appPageMethodName == 'browse') return 'story';
    if(appPageModuleName == 'my' || appPageModuleName == 'user') return appPageMethodName;

    const projectMethod = 'task|story|bug|build';
    if(appPageModuleName == 'project' && projectMethod.indexOf(appPageMethodName) != -1) return appPageMethodName;

    if(searchObjectList.indexOf(appPageModuleName) == -1) return 'bug';

    return appPageModuleName;
}

function getSearchUrl(searchType, searchValue)
{
    if(searchValue)
    {
        const reg = /[^0-9]/;
        let searchUrl = $.createLink('search', 'index');
        searchUrl += (searchUrl.indexOf('?') >= 0 ? '&' : '?') + 'words=' + searchValue + '&type=all';
        if(!searchType || searchType == 'all' || reg.test(searchValue)) return searchUrl;

        const types = searchType.split('-');
        const searchModule = types[0];
        const searchMethod = typeof(types[1]) == 'undefined' ? 'view' : types[1];
        searchUrl = $.createLink(searchModule, searchMethod, "id=" + searchValue);
        const assetType = ',story,issue,risk,opportunity,doc,';
        if(assetType.indexOf(',' + searchModule + ',') == -1) return searchUrl;

        const link = $.createLink('index', 'ajaxGetViewMethod' , 'objectID=' + searchValue + '&objectType=' + searchModule);
        $.get(link, function(data)
        {
            if(data) return $.createLink('assetlib', data, "id=" + searchValue);
        });
    }

    return false;
}

window.globalSearch = function(key, searchValue)
{
    let searchType = key;
    if(key == 'program')    searchType = 'program-product';
    if(key == 'deploystep') searchType = 'deploy-viewstep';

    const searchUrl = getSearchUrl(searchType, searchValue);
    if(searchUrl) openUrl(searchUrl);
};
