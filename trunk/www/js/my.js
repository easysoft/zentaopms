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
        document.write("<link rel='stylesheet' id='browsecss' href='" + cssFile + "' type='text/css' media='screen' />");
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
    if(!productMode) productMode = 'all';
    if(productMode == 'all')
    {
        $("#productID").append($("<option value='noclosed' id='switcher'>" + lblHideClosed + "</option>"));
    }
    else
    {
      $("#productID").append($("<option value='all' id='switcher'>" + lblShowAll + "</option>"));
    }
}

/* 选择产品。*/
function switchProduct(productID, module, method, extra)
{
    /* 如果传递过来的productID不是数字，则将其设置为产品选择方式。*/
    if(isNaN(productID))
    {
        $.cookie('productMode', productID);
        productID = 0;
    }

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

/* 选择文档库*/
function switchDocLib(libID, module, method, extra)
{
    if(module == 'doc')
    {
        if(method != 'view')
        {
            link = createLink(module, method, "rootID=" + libID);
        }
        else
        {
            link = createLink('doc', 'browse');
        }
    }
    /* tree，需要单独传递参数。*/
    else if(module == 'tree')
    {
        link = createLink(module, method, "rootID=" + libID + '&type=' + extra);
    }
    location.href=link;
}

/* 记住最后选择的产品id。*/
function saveProduct()
{
    if($('#productID')) $.cookie('lastProduct', $('#productID').val());
}

/* 设置项目选择器。*/
function setProjectSwitcher()
{
    projectMode = $.cookie('projectMode');
    if(!projectMode) projectMode = 'all';
    if(projectMode == 'all')
    {
        $("#projectID").append($("<option value='noclosed' id='switcher'>" + lblHideClosed + "</option>"));
    }
    else
    {
      $("#projectID").append($("<option value='all' id='switcher'>" + lblShowAll + "</option>"));
    }
}

/* 选择项目。*/
function switchProject(projectID, module, method)
{
    /* 如果传递过来的projectID不是数字，则将其设置为产品选择方式。*/
    if(isNaN(projectID))
    {
        $.cookie('projectMode', projectID);
        projectID = 0;
    }

    /* 如果是build模块，而且是edit方法，跳转地址改为project-build-xx.html。*/
    if(module == 'build' && method == 'edit')
    {
        module = 'project';
        method = 'build';
    }
    link = createLink(module, method, 'projectID=' + projectID);
    location.href=link;
}

/* 记住最后选择的项目id。*/
function saveProject()
{
    if($('#projectID')) $.cookie('lastProject', $('#projectID').val());
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
            $(this).after(' <a class="helplink" href=http://www.zentaoms.com/goto.php?item=fieldref&extra=lang=' + clientLang + ',module=' + currentModule + ',method=' + currentMethod + ',field=' + currentFieldName + ' target="_blank">?</a> ');
        }
    );
}

/* select the language. */
function selectLang(lang)
{
    $.cookie('lang', lang);
    location.href = location.href;
}

/* add one option of a select to another select. */
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

/* move one selected option from a select. */
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

/* move one selected option up from a select. */
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

/* move one selected option down from a select. */
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

/* select all items of a select. */
function selectItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=ItemList.length-1;x>=0;x--)
    {
        var opt = ItemList.options[x];
        opt.selected = true;
    }
}


/* 需要不需要ping，已保证session不过期。 */
needPing = true;

/* 自动执行的代码。*/
$(document).ready(function() 
{
    setNowrapObjTitle();
    setRequiredFields();
    setHelpLink();
    setProductSwitcher();
    setProjectSwitcher();
    saveProduct();
    saveProject();
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
