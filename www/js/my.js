/* 根据不同的浏览器加载相应的修正样式表文件。*/
function loadFixedCSS()
{
    cssFile = '';
    if($.browser.msie && Math.floor(parseInt($.browser.version)) == 6)
    {
        cssFile = themeRoot + 'ie.6.css';
    }
    else if($.browser.mozilla) 
    {
        cssFile = themeRoot + 'firefox.css';
    }
    if(cssFile != '')
    {
        document.write("<link rel='stylesheet' href='" + cssFile + "' type='text/css' media='screen' />");
    }
}

/* JS版本的createLink。*/
function createLink(moduleName, methodName, vars, viewType)
{
    link = webRoot;
    if(!viewType) viewType = defaultView;
    vars = vars.split('&');
    for(i = 0; i < vars.length; i ++) vars[i] = vars[i].split('=');
    if(requestType == 'PATH_INFO')
    {
        link += moduleName + requestFix + methodName;
        if(pathType == "full")
        {
            for(i = 0; i < vars.length; i ++) link += requestFix + vars[i][0] + requestFix + vars[i][1];
        }
        else
        {
            for(i = 0; i < vars.length; i ++) link += requestFix + vars[i][1];
        }
        link += '.' + viewType;
    }
    else
    {
        link += '?' + moduleVar + '=' + moduleName + '&' + methodVar + '=' + methodName + '&' + viewVar + '=' + viewType;
        for(i = 0; i < vars.length; i ++) link += '&' + vars[i][0] + '=' + vars[i][1];
    }
    return link;
}
