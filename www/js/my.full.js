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
 * Show drop menu. 
 * 
 * @param  string $objectType product|project
 * @param  int    $objectID 
 * @param  string $module 
 * @param  string $method 
 * @param  string $extra 
 * @access public
 * @return void
 */
function showDropMenu(objectType, objectID, module, method, extra)
{
    console.log(arguments);
    var li = $('#currentItem').closest('li');
    if(li.hasClass('show')) {li.removeClass('show'); return;}

    if(!li.data('showagain'))
    {
        li.data('showagain', true);
        $(document).click(function() {li.removeClass('show');});
        $('#dropMenu, #currentItem').click(function(e){e.stopPropagation();});
    }
    $.get(createLink(objectType, 'ajaxGetDropMenu', "objectID=" + objectID + "&module=" + module + "&method=" + method + "&extra=" + extra), function(data){ $('#dropMenu').html(data).find('#search').focus();});

    li.addClass('show');
}

/**
 * Show drop result. 
 * 
 * @param  objectType $objectType 
 * @param  objectID $objectID 
 * @param  module $module 
 * @param  method $method 
 * @param  extra $extra 
 * @access public
 * @return void
 */
function showDropResult(objectType, objectID, module, method, extra)
{
    $('#resultList').load(createLink(objectType, 'ajaxGetDropMenu', "objectID=" + objectID + "&module=" + module + "&method=" + method + "&extra=" + extra) + ' #searchResult');
}

/**
 * Search items. 
 * 
 * @param  string $keywords 
 * @param  string $objectType 
 * @param  int    $objectID 
 * @param  string $module 
 * @param  string $method 
 * @param  string $extra 
 * @access public
 * @return void
 */
function searchItems(keywords, objectType, objectID, module, method, extra)
{
    if(keywords == '')
    {
        showMenu = 0;
        showDropResult(objectType, objectID, module, method, extra);
        setTimeout(function(){$("#dropMenu #search").focus();}, 300);
    }
    else
    {
        keywords = encodeURI(keywords);
        if(keywords != '-') $.get(createLink(objectType, 'ajaxGetMatchedItems', "keywords=" + keywords + "&module=" + module + "&method=" + method + "&extra=" + extra), function(data){$('#searchResult').html(data);});
    }
}

/**
 * Show or hide more items. 
 * 
 * @access public
 * @return void
 */
function switchMore()
{
    $('#search').width($('#search').width()).focus();
    $('#moreMenu').width($('#defaultMenu').outerWidth());
    $('#searchResult').toggleClass('show-more');
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
        $('#' + requiredFields[i]).closest('td,th').prepend("<div class='required required-wrapper'></div>");
        var colEle = $('#' + requiredFields[i]).closest('[class*="col-"]');
        if(colEle.parent().hasClass('form-group')) colEle.addClass('required');
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
    });

    if($('a.helplink').size()) $("a.helplink").colorbox({width:600, height:240, iframe:true, transition:'none', scrolling:false});
}

/**
 * Set paceholder. 
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
            if($('#' + key).prop('tagName') == 'INPUT')
            {
                $("#" + key).attr('placeholder', holders[key]);
            }
            else
            {
                $("#" + key).parent().append(holders[key]);
            }
        }
    }
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
 * Hide tree box 
 * 
 * @param  string $treeType 
 * @access public
 * @return void
 */
function hideTreeBox(treeType)
{
    $.cookie(treeType, 'hide', {expires:config.cookieLife, path:config.webRoot});
    $('.outer').addClass('hide-side');
    $('.side-handle .icon-caret-left').removeClass('icon-caret-left').addClass('icon-caret-right');
}

/**
 * Show tree box 
 * 
 * @param  string $treeType 
 * @access public
 * @return void
 */
function showTreeBox(treeType)
{
    $.cookie(treeType, 'show', {expires:config.cookieLife, path:config.webRoot});
    $('.outer').removeClass('hide-side');
    $('.side-handle .icon-caret-right').removeClass('icon-caret-right').addClass('icon-caret-left');
}

/**
 * Toggle tree menu.
  
 * @access public
 * @return void
 */
function toggleTreeBox()
{
    var treeType = $('.side-handle').data('id');
    if(typeof treeType == 'undefined' || treeType == null) return;
    if($.cookie(treeType) == 'hide') hideTreeBox(treeType);

    $('.side-handle').toggle
    (
        function()
        {
            if($.cookie(treeType) == 'hide') return showTreeBox(treeType);
            hideTreeBox(treeType);
        }, 
        function()
        {
            if($.cookie(treeType) == 'show') return hideTreeBox(treeType);
            showTreeBox(treeType);
        }
    );
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
 * Set theme.
 * 
 * @access public
 * @return void
 */
function selectTheme(theme)
{
    $.cookie('theme', theme, {expires:config.cookieLife, path:config.webRoot});
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
 * Set Outer box's width and height.
 * 
 * @access public
 * @return void
 */
function setOuterBox()
{
    if($('.outer > .side').length) $('.outer').addClass('with-side');

    var resetOuterHeight = function()
    {
        var height = $(window).height() - $('#header').height() - $('#footer').height() - 33;
        $('#wrap .outer').css('min-height', height);
        /* uncomment to ajust treebox height */
        // $('#treebox').css('min-height', height - $('#featurebar').height() - 18);
    }

    $(window).resize(resetOuterHeight);
    resetOuterHeight();
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
 * Set form action and submit.
 * 
 * @param  url    $actionLink 
 * @param  string $hiddenwin 'hiddenwin'
 * @access public
 * @return void
 */
function setFormAction(actionLink, hiddenwin)
{
  if(hiddenwin) $('form').attr('target', hiddenwin);
  $('form').attr('action', actionLink).submit();
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
        maxWidth  = bodyWidth - 470; // The side bar's width is 336, and add some margins.
    }

    if($(image).width() > maxWidth) $(image).attr('width', maxWidth);
    $(image).wrap('<a href="' + $(image).attr('src') + '" target="_blank"></a>');
}

/**
 * Set the repo link.
 * 
 * @access public
 * @return void
 */
function setRepoLink()
{
    if($('.repolink').size()) $('.repolink').colorbox({width:960, height:600, iframe:true, transition:'elastic', speed:350, scrolling:true});
}

/* Set the colorbox of export. */
function setExport()
{
   // if($('.export').size()) $(".export").colorbox({width:650, height:240, iframe:true, transition:'none', scrolling:true});
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
    link = createLink('user', 'ajaxGetContactUsers', 'listID=' + contactListID);
    $.get(link, function(users)
    {
        $('#' + mailto).replaceWith(users);
        $('#' + mailto + '_chzn').remove();
        $('#' + mailto).chosen({no_results_text: noResultsMatch});
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
    $('.tablesorter tr :checkbox').click(function(){clickInCheckbox = 1;});

    $('.tablesorter tr').click(function()
    {
        if(document.activeElement.type != 'select-one' && document.activeElement.type != 'text')
        {
            if(typeof(clickInCheckbox) != 'undefined' && clickInCheckbox == 1)
            {
                clickInCheckbox = 0;
            }
            else
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
                $('#bymoduleTab').removeClass('active');
            }
            else
            {
                $('#' + browseType + 'Tab').removeClass('active');
            }
            $('#bysearchTab').addClass('active');
            ajaxGetSearchForm();
            $('#querybox').addClass('show');
        },
        function()
        {
            if(browseType == 'bymodule')
            {
                $('#bymoduleTab').addClass('active');
            }
            else
            {
                $('#' + browseType +'Tab').addClass('active');
            }
            $('#bysearchTab').removeClass('active');
            $('#querybox').removeClass('show');
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
        $.get(createLink('search', 'buildForm'), function(data)
        {
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

/**
 * Delete item use ajax.
 * 
 * @param  string url 
 * @param  string replaceID 
 * @param  string notice 
 * @access public
 * @return void
 */
function ajaxDelete(url, replaceID, notice)
{
    if(confirm(notice))
    {
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    $('#' + replaceID).wrap("<div id='tmpDiv'></div>");
                    $('#tmpDiv').load(document.location.href + ' #' + replaceID, function()
                    {
                        $('#tmpDiv').replaceWith($('#tmpDiv').html());
                        if(typeof sortTable == 'function')
                        {
                            sortTable(); 
                        }
                        else
                        {
                            $('.colored').colorize();
                            $('tfoot td').css('background', 'white').unbind('click').unbind('hover');
                        }
                    });
                }
            }
        });
    }
}

/**
 * Set modal load content with ajax or iframe
 * 
 * @access public
 * @return void
 */
function setModal()
{
    jQuery.fn.modalTrigger = function(setting)
    {
        initModalFrame(setting);

        $(this).click(function(event)
        {
            var $e   = $(this);
            var url  = (setting ? setting.url : false) || $e.attr('href') || $e.data('url');
            var type = (setting ? setting.type : false) || $e.hasClass('iframe') ? 'iframe' : ($e.data('type') || 'ajax');
            if(type == 'iframe')
            {
                var options = 
                {
                    url: url,
                    width: $e.data('width') || 800,
                    height: $e.data('height') || 'auto',
                    icon: $e.data('icon') || '?',
                    title: $e.data('title') || $e.attr('title') || $e.text(),
                    name: $e.data('name') || 'modalIframe'
                }
                options = $.extend(options, setting);
                
                if(options.height != 'auto') options.height += 'px';
                if(options.icon == '?')
                {
                    var i = $e.find("[class^='icon-']");
                    options.icon = i.length ? i.attr('class').substring(5) : 'file-text';
                }
                var modal = $('#ajaxModal').addClass('modal-loading');
                modal.html("<div class='modal-dialog modal-iframe' style='width: {width}px; height: {height}'><div class='modal-content'><div class='modal-header'><button class='close' data-dismiss='modal'>×</button><h4 class='modal-title'><i class='icon-{icon}'></i> {title}</h4></div><div class='modal-body'><iframe id='{name}' name='{name}' src='{url}' frameborder='no' allowtransparency='true' scrolling='auto' hidefocus='' style='width: 100%; height: 100%; left: 0px;'></iframe></div></div></div>".format(options));

                var frame = document.getElementById(options.name);
                frame.onload = frame.onreadystatechange = function()
                {
                    if (this.readyState && this.readyState != 'complete') return;
                    modal.removeClass('modal-loading');

                    try
                    {
                        var $frame = $(window.frames[options.name].document);
                        if($frame.find('#titlebar').length) modal.addClass('with-titlebar');
                        if(options.height == 'auto')
                        {
                            setTimeout(function()
                            {
                                modal.find('.modal-body').animate({height: $frame.find('body').addClass('body-modal').outerHeight()}, 100);
                            }, 100);
                        }
                    }
                    catch(e){}
                }
                modal.modal('show');
            }
            else
            {
                $('#ajaxModal').load(url, function()
                {
                    /* Set the width of modal dialog. */
                    if($e.data('width'))
                    {
                        var modalWidth = parseInt($e.data('width'));
                        $(this).data('width', modalWidth).find('.modal-dialog').css('width', modalWidth);
                    }

                    /* show the modal dialog. */
                    $('#ajaxModal').modal('show');
                });
            }

            /* Save the href to rel attribute thus we can save it. */
            $('#ajaxModal').attr('rel', url);

            return false;
        });
    }

    function initModalFrame(setting)
    {
        if($('#ajaxModal').length)
        {
            /* unbind all events */
            $('#ajaxModal').off('show.bs.modal shown.bs.modal hide.bs.modal hidden.bs.modal');
        }
        else
        {
            /* Addpend modal div. */
            $('<div id="ajaxModal" class="modal fade"></div>').appendTo('body');
        }

        /* rebind events */
        if(!setting) return;
        $ajaxModal = $('#ajaxModal');
        if(setting.afterShow && $.isFunction(setting.afterShow)) $ajaxModal.on('show.bs.modal', setting.afterShow);
        if(setting.afterShown && $.isFunction(setting.afterShown)) $ajaxModal.on('shown.bs.modal', setting.afterShown);
        if(setting.afterHide && $.isFunction(setting.afterHide)) $ajaxModal.on('hide.bs.modal', setting.afterHide);
        if(setting.afterHidden && $.isFunction(setting.afterHidden)) $ajaxModal.on('hidden.bs.modal', setting.afterHidden);
    }

    $('[data-toggle=modal], a.iframe').modalTrigger();
}

/**
 * Set table behavior
 * 
 * @access public
 * @return void
 */
function setTableBehavior()
{
    $('#wrap .table:not(.table-data, .table-form) tbody tr:not(.actie-disabled) td').click(function(){$(this).closest('tr').toggleClass('active');});
    $('#wrap .outer > .table, #wrap .outer > form > .table, #wrap .outer > .mian > .table, #wrap .outer > .mian > form > .table, #wrap .outer > .container > .table').not('.table-data, .table-form').addClass('table table-condensed table-hover table-striped table-borderless tablesorter');
}

/**
 * Make form condensed
 * 
 * @access public
 * @return void
 */
function condensedForm()
{
    $('.form-condensed legend').click(function()
    {
        $(this).closest('fieldset').toggleClass('collapsed');
    });
}

/**
 * Update data to the target element synchronous.
 * 
 * @access public
 * @return void
 */
function setSyncTrigger()
{
    $("[data-sync-target]").on('input propertychange', function()
    {
        var $this = $(this);
        var val = $this.prop('tagName') == 'INPUT' ? $this.val() : $this.html();
        var target = $($this.attr('data-sync-target'));
        console.log(target);
        if(target.prop('tagName') == 'INPUT') target.val(val);
        else target.html(val);
    });
}


/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function() 
{
    condensedForm();
    setModal();
    setTableBehavior();
    setForm();
    saveWindowSize();
    setDebugWin('white');
    setOuterBox();

    setRequiredFields();
    setPlaceholder();

    setExport();
    setRepoLink();

    autoCheck();
    toggleSearch();
    toggleTreeBox();

    setSyncTrigger();

    hideClearDataLink();

    $(window).resize(saveWindowSize);   // When window resized, call it again.

    if(needPing) setTimeout('setPing()', 1000 * 60);  // After 5 minutes, begin ping.

    $('.export').bind('click', function()
    {
        var checkeds = '';
        $(':checkbox').each(function()
        {
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

/* CTRL+g, auto focus on the search box. */
$(document).bind('keydown', 'Ctrl+g', function(evt)
{
    $('#searchQuery').attr('value', '');
    $('#searchQuery').focus();
    evt.stopPropagation( );  
    evt.preventDefault( );
    return false;
});

/* left, go to pre object. */
$(document).bind('keydown', 'left', function(evt)
{
    preLink = ($('#pre').attr("href"));
    if(typeof(preLink) != 'undefined') location.href = preLink;
});

/* right, go to next object. */
$(document).bind('keydown', 'right', function(evt)
{
    nextLink = ($('#next').attr("href"));
    if(typeof(nextLink) != 'undefined') location.href = nextLink;
});
