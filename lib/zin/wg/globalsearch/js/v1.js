/**
 * Get the search Type according to the current module and the current method.
 *
 * @access public
 * @return string
 */
window.getGlobalSearchType = function()
{
    if(vision == 'lite') return 'story';

    const appInfo    = $.apps.getLastApp();
    const moduleName = appInfo.iframe.contentWindow.config.currentModule;
    const methodName = appInfo.iframe.contentWindow.config.currentMethod;

    if(moduleName == 'product' && methodName == 'browse') return 'story';
    if(moduleName == 'my' || moduleName == 'user') return methodName;

    const projectMethod = 'task|story|bug|build';
    if(moduleName == 'project' && projectMethod.indexOf(methodName) != -1) return methodName;

    if(this.props.items.some(x => x.key === moduleName)) return 'bug';

    return moduleName;
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

window.handleGlobalSearch = function(key, searchValue)
{
    let searchType = key;
    if(key == 'program')    searchType = 'program-product';
    if(key == 'deploystep') searchType = 'deploy-viewstep';

    const searchUrl = getSearchUrl(searchType, searchValue);
    if(searchUrl) openUrl(searchUrl);
};
