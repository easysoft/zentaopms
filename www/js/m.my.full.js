/**
 * Create link. 
 * 
 * @param  string $moduleName 
 * @param  string $methodName 
 * @param  string $vars 
 * @param  string $viewType 
 * @access public
 * @return string
 */
function createLink(moduleName, methodName, vars, viewType, isOnlyBody)
{
    if(!viewType)   viewType   = config.defaultView;
    if(!isOnlyBody) isOnlyBody = false;
    if(vars)
    {
        vars = vars.split('&');
        for(i = 0; i < vars.length; i ++) vars[i] = vars[i].split('=');
    }
    if(config.requestType == 'PATH_INFO')
    {
        link = config.webRoot + moduleName + config.requestFix + methodName;
        if(vars)
        {
            if(config.pathType == "full")
            {
                for(i = 0; i < vars.length; i ++) link += config.requestFix + vars[i][0] + config.requestFix + vars[i][1];
            }
            else
            {
                for(i = 0; i < vars.length; i ++) link += config.requestFix + vars[i][1];
            }
        }
        link += '.' + viewType;
    }
    else
    {
        link = config.router + '?' + config.moduleVar + '=' + moduleName + '&' + config.methodVar + '=' + methodName + '&' + config.viewVar + '=' + viewType;
        if(vars) for(i = 0; i < vars.length; i ++) link += '&' + vars[i][0] + '=' + vars[i][1];
    }

    /* if page has onlybody param then add this param in all link. the param hide header and footer. */
    if(onlybody == 'yes' || isOnlyBody)
    {
        var onlybody = config.requestType == 'PATH_INFO' ? "?onlybody=yes" : '&onlybody=yes';
        link = link + onlybody;
    }
    return link;
}



/**
 * Set the ping url.
 * 
 * @access public
 * @return void
 */
function setPing()
{
    $('#hiddenwin').attr('src', createLink('misc', 'ping'));
}

/**
 * Set paceholder 
 * 
 * @access public
 * @return void
 */
function setPlaceholder()
{
    if(typeof(holders) != "undefined")
    {
        for(var key in holders)
        {
            $("#"+key).attr('placeholder', holders[key]);
        }
    }
}

/**
 * Set language.
 * 
 * @access public
 * @return void
 */
function selectLang(lang)
{
    $.cookie('lang', lang, {expires:config.cookieLife, path:config.webRoot});
    location.href = removeAnchor(location.href);
}


/**
 * Disable the submit button when submit form.
 * 
 * @access public
 * @return void
 */
function setForm()
{
    var formClicked = false;
    $('form').submit(function()
    {
        submitObj   = $(this).find(':submit');
        if($(submitObj).size() == 1)
        {
            submitLabel = $(submitObj).attr('value');
            $(submitObj).attr('disabled', 'disabled');
            $(submitObj).attr('value', lang.submitting);
            $(submitObj).addClass('button-d');
            formClicked = true;
        }
    });

    $("body").click(function()
    {
        if(formClicked)
        {
            $(submitObj).removeAttr('disabled');
            $(submitObj).attr('value', submitLabel);
            $(submitObj).removeClass('button-d');
        }
        formClicked = false;
    });
}

/**
 * Show detail.
 * 
 * @param  string $objectType 
 * @param  int    $objectID 
 * @access public
 * @return void
 */
function showDetail(objectType, objectID)
{
    $("div:jqmData(role='header')").next().css('margin-top', '5px');
    $.get(createLink(objectType, 'ajaxGetDetail', "objectID=" + objectID), function(data)
    {
        $('#item' + objectID).html(data);
        $.mobile.loading("hide");
    });      
}

/**
 * Set loading icon. 
 * 
 * @access public
 * @return void
 */
function setLoadingIcon()
{
    $('div.collapsible').bind('expand', 
    function()
    {
        $.mobile.loading("show", {text:'', textVisible:false, theme:'b', textonly:false, html:''}); 
    });
}

/**
 * Remove anchor from the url.
 * 
 * @param  string $url 
 * @access public
 * @return string
 */
function removeAnchor(url)
{
    pos = url.indexOf('#');
    if(pos > 0) return url.substring(0, pos);
    return url;
}

/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function() 
{
    setForm();
    setPlaceholder();
    setLoadingIcon();

    if(needPing) setTimeout('setPing()', 1000 * 60);  // After 5 minutes, begin ping.
    $(document).pjax("a[target!='hiddenwin'][id!='logout']", '#main');
    $(document).on('pjax:complete', function()
    {
        $('#main').trigger("pagecreate");
        var height = 0;
        $("div:jqmData(role='header')").find("div:jqmData(role='navbar')").each(function(){height = height + $(this).height()})
        $("div:jqmData(role='header')").next().css('margin-top', (height + 5) + 'px');
    });
});
