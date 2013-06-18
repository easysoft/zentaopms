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
 * Set the product switcher 
 * 
 * @access public
 * @return void
 */
function setProductSwitcher()
{
    productMode = $.cookie('productMode');
    if(!productMode) productMode = 'noclosed';
    if(productMode == 'all')
    {
        $("#productID").append($("<option value='noclosed' id='switcher'>" + config.lblHideClosed + "</option>"));
    }
    else
    {
        $("#productID").append($("<option value='all' id='switcher'>" + config.lblShowAll + "</option>"));
    }
}

/**
 * Search product in drop menu. 
 * 
 * @param  string  $keywords 
 * @param  int     $productID 
 * @param  string  $module 
 * @param  string  $method 
 * @param  mix     $extra 
 * @access public
 * @return void
 */
function searchProduct(keywords, $productID, module, method, extra)
{
    if(keywords == '')
    {
        showProductMenu = 0;
        showDropMenu(productID, module, method, extra)
    }
    else
    {
        $.get(createLink('product', 'searchProduct', "keywords=" + keywords + "&module=" + module + "&method=" + method + "&extra=" + extra), function(data){ $('#searchResult').html(data);});
    }
}

/**
 * Save the id of the product last visited.
 * 
 * @access public
 * @return void
 */
function saveProduct()
{
    if($('#productID')) $.cookie('lastProduct', $('#productID').val(), {expires:config.cookieLife, path:config.webRoot});
}

/**
 * Set project switcher 
 * 
 * @access public
 * @return void
 */
function setProjectSwitcher()
{
    projectMode = $.cookie('projectMode');
    if(!projectMode) projectMode = 'noclosed';
    if(projectMode == 'all')
    {
        $("#projectID").append($("<option value='noclosed' id='switcher'>" + config.lblHideClosed + "</option>"));
    }
    else
    {
        $("#projectID").append($("<option value='all' id='switcher'>" + config.lblShowAll + "</option>"));
    }
}

/**
 * Swtich project.
 * 
 * @param  int    $projectID 
 * @param  string $module 
 * @param  string $method 
 * @access public
 * @return void
 */
function switchProject(projectID, module, method, extra)
{
    /* The projec id is a string, use it as the project model. */
    if(isNaN(projectID))
    {
        $.cookie('projectMode', projectID, {expires:config.cookieLife, path:config.webRoot});
        projectID = 0;
    }

    /* Process task and build modules. */
    if(module == 'task' && (method == 'view' || method == 'edit' || method == 'batchedit'))
    {
        module = 'project';
        method = 'task';
    }
    if(module == 'build' && method == 'edit')
    {
        module = 'project';
        method = 'build';
    }

    if(module == 'project' && method == 'create') return;

    link = createLink(module, method, 'projectID=' + projectID);
    if(extra != '') link = createLink(module, method, 'projectID=' + projectID + '&type=' + extra);
    location.href = link;
}

/**
 * Save the id of the project last visited.
 * 
 * @access public
 * @return void
 */
function saveProject()
{
    if($('#projectID')) $.cookie('lastProject', $('#projectID').val(), {expires:config.cookieLife, path:config.webRoot});
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
 * Set required fields, add star class to them.
 * 
 * @access public
 * @return void
 */
function setRequiredFields()
{
    if(!config.requiredFields) return false;
    requiredFields = config.requiredFields.split(',');
    for(i = 0; i < requiredFields.length; i++)
    {
        $('#' + requiredFields[i]).after('<span class="star"> * </span>');
    }
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

/**
 * Get the window size and save to cookie.
 * 
 * @access public
 * @return void
 */
function saveWindowSize()
{
    width  = $(window).width(); 
    height = $(window).height();
    $.cookie('windowWidth',  width)
    $.cookie('windowHeight', height)
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
            $(submitObj).attr('value', config.submitting);
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
 * Set the max with of image.
 * 
 * @access public
 * @return void
 */
function setImageSize(image, maxWidth)
{
    /* If not set maxWidth, set it auto. */
    if(!maxWidth)
    {
        bodyWidth = $('body').width();
        maxWidth  = bodyWidth - 450; // The side bar's width is 336, and add some margins.
    }
    $('.content img').each(function()
    {
        if($(this).width() > maxWidth) $(this).attr('width', maxWidth);
    });
    $(image).wrap('<a href="' + $(image).attr('src') + '" target="_blank"></a>')
}

/**
 * Set mailto list from a contact list..
 * 
 * @param  string $mailto 
 * @param  int    $contactListID 
 * @access public
 * @return void
 */
function setMailto(mailto, contactListID)
{
    if(!contactListID) return;
    link = createLink('user', 'ajaxGetContactUsers', 'listID=' + contactListID);
    $.get(link, function(users)
    {
        $('#' + mailto).val(users);
    });
}

/**
 * Set comment. 
 * 
 * @access public
 * @return void
 */
function setComment()
{
    $('#commentBox').toggle();
    $('.ke-container').css('width', '100%');
    setTimeout(function() { $('#commentBox textarea').focus(); }, 50);
}

/**
 * Auto checked the checkbox of a row. 
 * 
 * @access public
 * @return void
 */
function autoCheck()
{
    $('.tablesorter tr :checkbox').click(function()
    {
        if($(this).attr('checked'))
        {
            $(this).attr('checked', false);
        }
        else
        {
            $(this).attr('checked', true);
        }
        return;
    });

    $('.tablesorter tr').click(function()
    {
        if(document.activeElement.type != 'select-one' && document.activeElement.type != 'text')
        {
            if($(this).find(':checkbox').attr('checked'))
            {
                $(this).find(':checkbox').attr('checked', false);
            }
            else
            {
                $(this).find(':checkbox').attr('checked', true);
            }
        }
    });
}

/**
 * Toogle the search form.
 * 
 * @access public
 * @return void
 */
function toggleSearch()
{
    $("#bysearchTab").toggle
    (
        function()
        {
            if(browseType == 'bymodule')
            {
                $('#treebox').addClass('hidden');
                $('.divider').addClass('hidden');
                $('#bymoduleTab').removeClass('active');
            }
            else
            {
                $('#' + browseType + 'Tab').removeClass('active');
            }
            $('#bysearchTab').addClass('active');
            ajaxGetSearchForm();
            $('#querybox').removeClass('hidden');
        },
        function()
        {
            if(browseType == 'bymodule')
            {
                $('#treebox').removeClass('hidden');
                $('.divider').removeClass('hidden');
                $('#bymoduleTab').addClass('active');
            }
            else
            {
                $('#' + browseType +'Tab').addClass('active');
            }
            $('#bysearchTab').removeClass('active');
            $('#querybox').addClass('hidden');
        } 
    );
}

/**
 * Ajax get search form 
 * 
 * @access public
 * @return void
 */
function ajaxGetSearchForm()
{
    if($('#querybox').html() == '')
    {
        $.get(createLink('search', 'buildForm'), function(data){
            $('#querybox').html(data);
        });
    }
}

/**
 * Hide the link of clearData.
 * 
 * @access public
 * @return void
 */
function hideClearDataLink()
{
    if(typeof showDemoUsers == 'undefined' || !showDemoUsers) $('#submenuclearData').addClass('hidden');
}

/**
 * add one option of a select to another select. 
 * 
 * @param  string $SelectID 
 * @param  string $TargetID 
 * @access public
 * @return void
 */
function addItem(SelectID,TargetID)
{
    ItemList = document.getElementById(SelectID);
    Target   = document.getElementById(TargetID);
    for(var x = 0; x < ItemList.length; x++)
    {
        var opt = ItemList.options[x];
        if (opt.selected)
        {
            flag = true;
            for (var y=0;y<Target.length;y++)
            {
                var myopt = Target.options[y];
                if (myopt.value == opt.value)
                {
                    flag = false;
                }
            }
            if(flag)
            {
                Target.options[Target.options.length] = new Option(opt.text, opt.value, 0, 0);
            }
        }
    }
}

/**
 * Remove one selected option from a select.
 * 
 * @param  string $SelectID 
 * @access public
 * @return void
 */
function delItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=ItemList.length-1;x>=0;x--)
    {
        var opt = ItemList.options[x];
        if (opt.selected)
        {
            ItemList.options[x] = null;
        }
    }
}

/**
 * move one selected option up from a select. 
 * 
 * @param  string $SelectID 
 * @access public
 * @return void
 */
function upItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=1;x<ItemList.length;x++)
    {
        var opt = ItemList.options[x];
        if(opt.selected)
        {
            tmpUpValue = ItemList.options[x-1].value;
            tmpUpText  = ItemList.options[x-1].text;
            ItemList.options[x-1].value = opt.value;
            ItemList.options[x-1].text  = opt.text;
            ItemList.options[x].value = tmpUpValue;
            ItemList.options[x].text  = tmpUpText;
            ItemList.options[x-1].selected = true;
            ItemList.options[x].selected = false;
            break;
        }
    }
}

/**
 * move one selected option down from a select. 
 * 
 * @param  string $SelectID 
 * @access public
 * @return void
 */
function downItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=0;x<ItemList.length;x++)
    {
        var opt = ItemList.options[x];
        if(opt.selected)
        {
            tmpUpValue = ItemList.options[x+1].value;
            tmpUpText  = ItemList.options[x+1].text;
            ItemList.options[x+1].value = opt.value;
            ItemList.options[x+1].text  = opt.text;
            ItemList.options[x].value = tmpUpValue;
            ItemList.options[x].text  = tmpUpText;
            ItemList.options[x+1].selected = true;
            ItemList.options[x].selected = false;
            break;
        }
    }
}

/**
 * select all items of a select. 
 * 
 * @param  string $SelectID 
 * @access public
 * @return void
 */
function selectItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=ItemList.length-1;x>=0;x--)
    {
        var opt = ItemList.options[x];
        opt.selected = true;
    }
}

/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function() 
{
    setForm();

    setRequiredFields();
    setPlaceholder();
    setProductSwitcher();
    setProjectSwitcher();
    saveProduct();
    saveProject();

    autoCheck();
    toggleSearch();

    hideClearDataLink();

    if(needPing) setTimeout('setPing()', 1000 * 60);  // After 5 minutes, begin ping.

    $('.export').bind('click', function()
    {
        var checkeds = '';
        $(':checkbox').each(function(){
            if($(this).attr('checked'))
            {
                var checkedVal = parseInt($(this).val());
                if(checkedVal != 0) checkeds = checkeds + checkedVal + ',';
            }
        })
        if(checkeds != '') checkeds = checkeds.substring(0, checkeds.length - 1);
        $.cookie('checkedItem', checkeds, {expires:config.cookieLife, path:config.webRoot});
    });
});
