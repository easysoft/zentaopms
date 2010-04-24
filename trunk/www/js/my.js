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

    /* 解决safari和chrome的caption问题。*/
    if($.browser.safari && $('.caption-tl'))
    {
        document.write("<style>caption{margin-bottom:15px}</style>");
    }
}

/* JS版本的createLink。*/
function createLink(moduleName, methodName, vars, viewType)
{
    link = webRoot;
    if(!viewType) viewType = defaultView;
    if(vars)
    {
        vars = vars.split('&');
        for(i = 0; i < vars.length; i ++) vars[i] = vars[i].split('=');
    }
    if(requestType == 'PATH_INFO')
    {
        link += moduleName + requestFix + methodName;
        if(vars)
        {
            if(pathType == "full")
            {
                for(i = 0; i < vars.length; i ++) link += requestFix + vars[i][0] + requestFix + vars[i][1];
            }
            else
            {
                for(i = 0; i < vars.length; i ++) link += requestFix + vars[i][1];
            }
        }
        link += '.' + viewType;
    }
    else
    {
        link += '?' + moduleVar + '=' + moduleName + '&' + methodVar + '=' + methodName + '&' + viewVar + '=' + viewType;
        if(vars) for(i = 0; i < vars.length; i ++) link += '&' + vars[i][0] + '=' + vars[i][1];
    }
    return link;
}

/* 快速跳转到某一个模块的某一个id。*/
function shortcut()
{
    objectType  = $('#searchType').attr('value');
    objectValue = $('#searchQuery').attr('value');
    if(objectType && objectValue)
    {
        location.href=createLink(objectType, 'view', "id=" + objectValue);
    }
}

/* 自动设置所有属性为nobr的title。*/
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

/* 设置产品选择器。*/
function setProductSwitcher()
{
    productMode = $.cookie('productMode');
    if(!productMode) productMode = 'showAll';
    if(productMode == 'showAll')
    {
        $("#productID").append($("<option value='hideClosed' id='switcher'>" + lblHideClosed + "</option>"));
    }
    else
    {
      $("#productID").append($("<option value='showAll' id='switcher'>" + lblShowAll + "</option>"));
    }
}

/* 选择产品。*/
function switchProduct(productID, module, method, extra)
{
    /* 如果传递过来的productID不是数字，则将其设置为产品选择方式。*/
    if(isNaN(productID)) $.cookie('productMode', productID);
    productID = 0;

    /* product, roadmap, bug, testcase, testtask，直接传递参数。*/
    if(module == 'product' || module == 'roadmap' || module == 'bug' || module == 'testcase' || module == 'testtask')
    {
        link = createLink(module, method, "productID=" + productID);
    }
    /* productplan, relase模块需要处理非browse和create的方法。*/
    else if(module == 'productplan' || module == 'release')
    {
        if(method != 'browse' && method != 'create') method = 'browse';
        link = createLink(module, method, "productID=" + productID);
    }
    /* tree，需要单独传递参数。*/
    else if(module == 'tree')
    {
        link = createLink(module, method, "productID=" + productID + '&type=' + extra);
    }
    location.href=link;
}

/* 选择项目。*/
function switchProject(projectID, module, method)
{
    /* 如果是build模块，而且是edit方法，跳转地址改为project-build-xx.html。*/
    if(module == 'build' && method == 'edit')
    {
        module = 'project';
        method = 'build';
    }
    link = createLink(module, method, 'projectID=' + projectID);
    location.href=link;
}

/* 选择用户。*/
function switchAccount(account, method)
{
    link = createLink('user', method, 'account=' + account);
    location.href=link;
}

/* 设置ping的地址，防止session超时。*/
function setPing()
{
    $('#hiddenwin').attr('src', createLink('misc', 'ping'));
}

/* 设置必填字段。*/
function setRequiredFields()
{
    if(!requiredFields) return false;
    requiredFields = requiredFields.split(',');
    for(i = 0; i < requiredFields.length; i++)
    {
        $('#' + requiredFields[i]).after('<span class="star"> * </span>');
    }
}

/* 设置帮助链接。*/
function setHelpLink()
{
    $('form input[id], form select[id], form textarea[id]').each(function()
        {
            if($(this).attr('type') == 'hidden' || $(this).attr('type') == 'file') return;
            currentFieldName = $(this).attr('name') ? $(this).attr('name') : $(this).attr('id');
            if(currentFieldName == 'submit' || currentFieldName == 'reset') return;
            if(currentFieldName.indexOf('[') > 0) currentFieldName = currentFieldName.substr(0, currentFieldName.indexOf('['));
            currentFieldName = currentFieldName.toLowerCase();
            $(this).after(' <a class="helplink" href=' + zentaoHelpRoot + '?lang=' + clientLang + '&module=' + currentModule + '&method=' + currentMethod + '&field=' + currentFieldName + '>?</a> ');
        }
    );
}

/* 需要不需要ping，已保证session不过期。 */
needPing = true;

/* 自动执行的代码。*/
$(document).ready(function() 
{
    setNowrapObjTitle();
    setRequiredFields();
    //setHelpLink();
    setProductSwitcher();
    if(needPing) setTimeout('setPing()', 1000 * 60 * 5);  // 5分钟之后开始ping。
});

/* CTRL+g 聚焦到搜索框。*/
$(document).bind('keydown', 'Ctrl+g', function(evt)
{
    $('#searchQuery').attr('value', '');
    $('#searchType').focus();
    evt.stopPropagation( );  
    evt.preventDefault( );
    return false;
});
