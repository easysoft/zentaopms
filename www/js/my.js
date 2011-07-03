/**
 * Load css file of special browser.
 * 
 * @access public
 * @return void
 */
function loadFixedCSS()
{
    cssFile = '';
    if($.browser.msie )
    {
        version = Math.floor(parseInt($.browser.version));
        cssFile = version == 6 ? config.themeRoot + '/browser/ie.6.css' : config.themeRoot + 'browser/ie.css';
    }
    else if($.browser.mozilla) 
    {
        cssFile = config.themeRoot + '/browser/firefox.css';
    }
    else if($.browser.opera) 
    {
        cssFile = config.themeRoot + '/browser/opera.css';
    }
    else if($.browser.safari) 
    {
        cssFile = config.themeRoot + '/browser/safari.css';
    }
    else if($.browser.chrome) 
    {
        cssFile = config.themeRoot + '/browser/chrome.css';
    }

    if(cssFile != '')
    {
        document.write("<link rel='stylesheet' href='" + cssFile + "' type='text/css' media='screen' />");
    }
}

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
function createLink(moduleName, methodName, vars, viewType)
{
    if(!viewType) viewType = config.defaultView;
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
    return link;
}

/**
 * Go to the view page of one object.
 * 
 * @access public
 * @return void
 */
function shortcut()
{
    objectType  = $('#searchType').attr('value');
    objectValue = $('#searchQuery').attr('value');
    if(objectType && objectValue)
    {
        location.href=createLink(objectType, 'view', "id=" + objectValue);
    }
}

/**
 * Set the titile of all objects which class is .nobr.
 * 
 * @access public
 * @return void
 */
function setNowrapObjTitle()
{
    $('.nobr').each(function (i) 
    {
        if($.browser.mozilla) 
        {
            this.title = this.textContent;
        }
        else
        {
            this.title = this.innerText;
        }
    })
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
 * Switch the product.
 * 
 * @param  int    $productID 
 * @param  string $module 
 * @param  string $method 
 * @param  string  $extra 
 * @access public
 * @return void
 */
function switchProduct(productID, module, method, extra)
{
    /* If the product id is a string, use it as the product browse mode. */
    if(isNaN(productID))
    {
        $.cookie('productMode', productID, {expires:config.cookieLife, path:config.webRoot});
        productID = 0;
    }

    /* Module is product, roadmap, bug, testcase or testtask. switch directly. */
    if(module == 'product' || module == 'roadmap' || module == 'bug' || module == 'testcase' || module == 'testtask')
    {
        link = createLink(module, method, "productID=" + productID);
    }
    /* Module is productplan, relase, must process method not browse and create. */
    else if(module == 'productplan' || module == 'release')
    {
        if(method != 'browse' && method != 'create') method = 'browse';
        link = createLink(module, method, "productID=" + productID);
    }
    /* Module is tree. */
    else if(module == 'tree')
    {
        link = createLink(module, method, "productID=" + productID + '&type=' + extra);
    }
    location.href=link;
}

/**
 * Switch doc library.
 * 
 * @param  int    $libID 
 * @param  string $module 
 * @param  string $method 
 * @param  string $extra 
 * @access public
 * @return void
 */
function switchDocLib(libID, module, method, extra)
{
    if(module == 'doc')
    {
        if(method != 'view' && method != 'edit')
        {
            link = createLink(module, method, "rootID=" + libID);
        }
        else
        {
            link = createLink('doc', 'browse');
        }
    }
    else if(module == 'tree')
    {
        link = createLink(module, method, "rootID=" + libID + '&type=' + extra);
    }
    location.href=link;
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
function switchProject(projectID, module, method)
{
    /* The projec id is a string, use it as the project model. */
    if(isNaN(projectID))
    {
        $.cookie('projectMode', projectID, {expires:config.cookieLife, path:config.webRoot});
        projectID = 0;
    }

    /* Process task and build modules. */
    if(module == 'task' && (method == 'view' || method == 'edit'))
    {
        module = 'project';
        method = 'task';
    }
    if(module == 'build' && method == 'edit')
    {
        module = 'project';
        method = 'build';
    }
    link = createLink(module, method, 'projectID=' + projectID);
    location.href=link;
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
 * Switch account 
 * 
 * @param  string $account 
 * @param  string $method 
 * @access public
 * @return void
 */
function switchAccount(account, method)
{
    if(method == 'dynamic')
    {
        link = createLink('user', method, 'period=' + period + '&account=' + account);
    }
    else
    {
        link = createLink('user', method, 'account=' + account);
    }
    location.href=link;
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
 * Set the help links of forum's items.
 * 
 * @access public
 * @return void
 */
function setHelpLink()
{
    if(!$.cookie('help')) $.cookie('help', 'off', {expires:config.cookieLife, path:config.webRoot});
    className = $.cookie('help') == 'off' ? 'hidden' : '';

    $('form input[id], form select[id], form textarea[id]').each(function()
        {
            if($(this).attr('type') == 'hidden' || $(this).attr('type') == 'file') return;
            currentFieldName = $(this).attr('name') ? $(this).attr('name') : $(this).attr('id');
            if(currentFieldName == 'submit' || currentFieldName == 'reset') return;
            if(currentFieldName.indexOf('[') > 0) currentFieldName = currentFieldName.substr(0, currentFieldName.indexOf('['));
            currentFieldName = currentFieldName.toLowerCase();
            helpLink = createLink('help', 'field', 'module=' + config.currentModule + '&method=' + config.currentMethod + '&field=' + currentFieldName);
            $(this).after(' <a class="helplink ' + className + '" href=' + helpLink + ' target="_blank">?</a> ');
        }
    );

    if($('a.helplink').size()) $("a.helplink").colorbox({width:600, height:240, iframe:true, transition:'elastic', speed:350, scrolling:false});
}

/**
 * Toggle the help links.
 * 
 * @access public
 * @return void
 */
function toggleHelpLink()
{
    $('.helplink').toggle();
    if($.cookie('help') == 'off') return $.cookie('help', 'on',  {expires:config.cookieLife, path:config.webRoot});
    if($.cookie('help') == 'on')  return $.cookie('help', 'off', {expires:config.cookieLife, path:config.webRoot});
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
    location.href = location.href;
}

/**
 * Set theme.
 * 
 * @access public
 * @return void
 */
function selectTheme(theme)
{
    $.cookie('theme', theme, {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}

/**
 * Set the about link. 
 * 
 * @access public
 * @return void
 */
function setAbout()
{
    if($('a.about').size()) $("a.about").colorbox({width:900, height:330, iframe:true, transition:'elastic', speed:500, scrolling:false});
}

/**
 * Set the css of the iframe.
 * 
 * @param  string $color 
 * @access public
 * @return void
 */
function setDebugWin(color)
{  
    if($.browser.msie && $('.debugwin').size() == 1)
    {
        var debugWin = $(".debugwin")[0].contentWindow.document;
        $("body", debugWin).append("<style>body{background:" + color + "}</style>");
    }
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
function setImageSize()
{
    bodyWidth = $('body').width();
    maxWidth = bodyWidth - 420; // The side bar's width is 336, and add some margins.
    $('.content image').each(function()
    {
        if($(this).width() > maxWidth) $(this).attr('width', maxWidth);
    });
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
          if($(this).find(':checkbox').attr('checked'))
          {
              $(this).find(':checkbox').attr('checked', false);
          }
          else
          {
              $(this).find(':checkbox').attr('checked', true);
          }
      });
}

/**
 * Show the search or reduction the style. 
 * 
 * @access public
 * @return void
 */
function togglesearch()
{
    $("#bysearchTab").toggle(
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
/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function() 
{
    setNowrapObjTitle();
    setRequiredFields();
    setHelpLink();
    setProductSwitcher();
    setProjectSwitcher();
    setAbout();
    saveProduct();
    saveProject();
    if(needPing) setTimeout('setPing()', 1000 * 60 * 5);  // After 5 minus, begin ping.
    setForm();
    setImageSize();
    autoCheck();
    togglesearch();
});

/* CTRL+g, auto focus on the search box. */
$(document).bind('keydown', 'Ctrl+g', function(evt)
{
    $('#searchQuery').attr('value', '');
    $('#searchType').focus();
    evt.stopPropagation( );  
    evt.preventDefault( );
    return false;
});
