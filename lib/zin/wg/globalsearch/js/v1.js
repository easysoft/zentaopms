window.globalSearch = function(key, searchValue)
{
    openUrl(globalSearchUrl + searchValue + '&type=' + key);
};
