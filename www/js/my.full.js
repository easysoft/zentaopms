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
 * Bind Event for searchbox
 * 
 */
function setSearchBox()
{
    $('#typeSelector a').click(function()
    {
        $('#typeSelector li.active').removeClass('active');
        var $this = $(this);
        $this.closest('li').addClass('active');
        $("#searchType").val($this.data('value'));
        $("#searchTypeName").text($this.text());
    });
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
    $.get(createLink(objectType, 'ajaxGetDropMenu', "objectID=" + objectID + "&module=" + module + "&method=" + method + "&extra=" + extra), function(data){ $('#dropMenu').html(data); setTimeout(function(){$("#dropMenu #search").focus();}, 300);});
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

    $("a.helplink").modalTrigger({width:600, type:'iframe'});
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

    setTimeout(function(){$('.outer.with-side').addClass('with-transition')}, 1000);
}

/**
 * set tree menu.
  
 * @access public
 * @return void
 */
function setTreeBox()
{
    if($('.outer > .side').length) $('.outer').addClass('with-side');
    toggleTreeBox();
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
    if($('.sub-featurebar').length) $('#featurebar').addClass('with-sub');

    var side   = $('#wrap .outer > .side');
    var resetOuterHeight = function()
    {
        var sideH  = side.length ? (side.outerHeight() + $('#featurebar').outerHeight() + 20) : 0;
        var height = Math.max(sideH, $(window).height() - $('#header').height() - $('#footer').height() - 33);
        if(navigator.userAgent.indexOf("MSIE 8.0") >= 0) height -= 40;
        $('#wrap .outer').css('min-height', height);
    }

    side.resize(resetOuterHeight);
    $(window).resize(resetOuterHeight);
    resetOuterHeight();
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
        if($(submitObj).size() >= 1)
        {
            var isBtn = submitObj.prop('tagName') == "BUTTON";
            submitLabel = isBtn ? $(submitObj).html() : $(submitObj).attr('value');
            $(submitObj).attr('disabled', 'disabled');
            var submitting = submitObj.attr('data-submitting') || lang.submitting;
            if(isBtn) submitObj.text(submitting);
            else $(submitObj).attr('value', submitting);
            formClicked = true;
        }
    });

    $("body").click(function()
    {
        if(formClicked)
        {
            $(submitObj).removeAttr('disabled');
            if(submitObj.prop('tagName') == "BUTTON")
            {
                submitObj.text(submitLabel);
            }
            else
            {
                $(submitObj).attr('value', submitLabel);
            }
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
    else $('form').removeAttr('target');

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
 * Set the modal trigger to link.
 * 
 * @access public
 * @return void
 */
function setModalTriggerLink()
{
    $('.repolink').modalTrigger({width:960, type:'iframe'});
    $(".export").modalTrigger({width:650, type:'iframe'});
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
        $('#' + mailto + '_chosen').remove();
        $('#' + mailto).chosen(defaultChosenOptions);
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
    $(document).on('click', '.tablesorter tr :checkbox', function(){clickInCheckbox = 1;});

    $(document).on('click', '.tablesorter tr', function()
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
                        $('#' + replaceID).find('[data-toggle=modal], a.iframe').modalTrigger();
                    });
                }
            }
        });
    }
}

/**
 * Judge the string is a integer number
 * 
 * @access public
 * @return bool
 */
function isNum(s)
{
    if(s!=null)
    {
        var r, re;
        re = /\d*/i;
        r = s.match(re);
        return (r == s) ? true : false;
    }
    return false;
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
        return $(this).each(function()
        {
            var $this = $(this);
            $this.off('click.modalTrigger.zui');

            $this.on('click.modalTrigger.zui', function(event)
            {
                var $e   = $(this);
                if($e.closest('.body-modal').length) return;

                if($e.hasClass('disabled')) return false;

                var url  = (setting ? setting.url : false) || $e.attr('href') || $e.data('url');
                var type = (setting ? setting.type : false) || $e.hasClass('iframe') ? 'iframe' : ($e.data('type') || 'ajax');
                if(type == 'iframe')
                {
                    var options = 
                    {
                        url:        url,
                        width:      $e.data('width') || 800,
                        height:     $e.data('height') || 'auto',
                        icon:       $e.data('icon') || '?',
                        title:      $e.data('title') || $e.attr('title') || $e.text(),
                        name:       $e.data('name') || 'modalIframe',
                        cssClass:   $e.data('class'),
                        headerless: $e.data('headerless') || false,
                        center:     $e.data('center') || true
                    };

                    if(options.icon == '?')
                    {
                        var i = $e.find("[class^='icon-']");
                        options.icon = i.length ? i.attr('class').substring(5) : 'file-text';
                    }

                    showIframeModal($.extend(options, setting));
                }
                else
                {
                    initModalFrame();
                    $.get(url, function(data)
                    {
                        var ajaxModal = $('#ajaxModal');
                        if(data.indexOf('modal-dialog') < 0)
                        {
                            data = "<div class='modal-dialog modal-ajax' style='width: {width};'><div class='modal-content'><div class='modal-header'><button class='close' data-dismiss='modal'>×</button><h4 class='modal-title'><i class='icon-{icon}'></i> {title}</h4></div><div class='modal-body' style='height:{height}'>{content}</div></div></div>".format($.extend({content: data}, options));
                        }
                        ajaxModal.html(data);

                        /* Set the width of modal dialog. */
                        if($e.data('width'))
                        {
                            var modalWidth = parseInt($e.data('width'));
                            $(this).data('width', modalWidth).find('.modal-dialog').css('width', modalWidth);
                            ajustModalPosition();
                        }

                        ajaxModal.modal('show');
                    });
                }

                /* Save the href to rel attribute thus we can save it. */
                $('#ajaxModal').attr('rel', url);

                return false;
            });
        });
    }

    function showIframeModal(settings)
    {
        var options = 
        {
            width:      800,
            height:     'auto',
            icon:       '?',
            title:      '',
            name:       'modalIframe',
            cssClass:   '',
            headerless: false,
            waittime:   0,
            center:     true
        }
        
        if(typeof(settings) == 'string')
        {
            options.url = settings;
        }
        else
        {
            options = $.extend(options, settings);
        }

        initModalFrame(options);

        if(isNum(options.height.toString())) options.height += 'px';
        if(isNum(options.width.toString())) options.width += 'px';
        if(options.size == 'fullscreen')
        {
            var $w = $(window);
            options.width = $w.width();
            options.height = $w.height();
            options.cssClass += ' fullscreen';
        }
        if(options.headerless)
        {
            options.cssClass += ' hide-header';
        }
        if(typeof(options.url) == 'undefined' || !options.url) return false;

        var modal = $('#ajaxModal').addClass('modal-loading').data('first', true);

        modal.html("<div class='icon-spinner icon-spin loader'></div><div class='modal-dialog modal-iframe' style='width: {width};'><div class='modal-content'><div class='modal-header'><button class='close' data-dismiss='modal'>×</button><h4 class='modal-title'><i class='icon-{icon}'></i> {title}</h4></div><div class='modal-body' style='height:{height}'><iframe id='{name}' name='{name}' src='{url}' frameborder='no' allowtransparency='true' scrolling='auto' hidefocus='' style='width: 100%; height: 100%; left: 0px;'></iframe></div></div></div>".format(options));

        var modalBody = modal.find('.modal-body'), dialog = modal.find('.modal-dialog');
        if(options.cssClass)
        {
            dialog.addClass(options.cssClass);
        }

        if(options.waittime > 0)
        {
            options.waitingFuc = setTimeout(function(){showModal(options, modal, modalBody, dialog);}, options.waittime );
        }

        var frame = document.getElementById(options.name);
        frame.onload = frame.onreadystatechange = function()
        {
            if(this.readyState && this.readyState != 'complete') return;
            if(modal.data('first') && (!modal.hasClass('modal-loading'))) return;
            if(!modal.data('first')) modal.addClass('modal-loading');

            if(options.waittime > 0)
            {
                clearTimeout(options.waitingFuc);
            }
            showModal(options, modal, modalBody, dialog);
        }
        modal.modal('show');
    }

    function showModal(options, modal, modalBody, dialog)
    {
        modalBody.css('height', options.height - modal.find('.modal-header').outerHeight());
        try
        {
            var frame$ = window.frames[options.name].$;
            if(frame$('#titlebar').length)
            {
                modal.addClass('with-titlebar');
                if(options.size == 'fullscreen')
                {
                    modalBody.css('height', options.height);
                }
            }
            if(options.height == 'auto')
            {
                var $framebody = frame$('body');
                setTimeout(function()
                {
                    modal.removeClass('fade');
                    var fbH = $framebody.addClass('body-modal').outerHeight();
                    frame$('#titlebar > .heading a').each(function()
                    {
                        var $a = frame$(this);
                        $a.replaceWith("<strong class='heading-title'>" + $a.text() + "</strong>");
                    });
                    if(typeof fbH == 'object') fbH = $framebody.height();
                    modalBody.css('height', fbH);
                    ajustModalPosition();
                    if(modal.data('first')) modal.data('first', false);
                    modal.removeClass('modal-loading').addClass('fade');
                }, 100);

                $framebody.resize(function()
                {
                    var fbH = $framebody.outerHeight();
                    if(typeof fbH == 'object') fbH = $framebody.height();
                    modalBody.css('height', fbH);
                    ajustModalPosition();
                });
            }
            else
            {
                modal.removeClass('modal-loading');
            }

            if(frame$)
            {
                frame$.extend({'closeModal': $.closeModal});
            }
        }
        catch(e)
        {
            modal.removeClass('modal-loading');
        }
    }

    function initModalFrame(setting)
    {
        if($('#ajaxModal').length)
        {
            /* unbind all events */
            $('#ajaxModal').attr('class', 'modal fade').off('show.zui.modal shown.zui.modal hide.zui.modal hidden.zui.modal');
        }
        else
        {
            /* Addpend modal div. */
            $('<div id="ajaxModal" class="modal fade"></div>').appendTo('body');
        }

        $ajaxModal = $('#ajaxModal');
        $ajaxModal.data('cancel-reload', false);

        $.extend({'closeModal':function(callback, location)
        {
            $ajaxModal.on('hidden.zui.modal', function()
            {
                if(location && (!$ajaxModal.data('cancel-reload')))
                {
                    if(location == 'this') window.location.reload();
                    else window.location = location;
                }
                if(callback && $.isFunction(callback)) callback();
            });
            $ajaxModal.modal('hide');
        }, 'cancelReloadCloseModal': function(){$ajaxModal.data('cancel-reload', true);}});

        /* rebind events */
        if(!setting) return;
        if(setting.afterShow && $.isFunction(setting.afterShow)) $ajaxModal.on('show.zui.modal', setting.afterShow);
        if(setting.afterShown && $.isFunction(setting.afterShown)) $ajaxModal.on('shown.zui.modal', setting.afterShown);
        if(setting.afterHide && $.isFunction(setting.afterHide)) $ajaxModal.on('hide.zui.modal', setting.afterHide);
        if(setting.afterHidden && $.isFunction(setting.afterHidden)) $ajaxModal.on('hidden.zui.modal', setting.afterHidden);
    }

    function ajustModalPosition(position, dialog)
    {
        position = position || 'fit';
        if(!dialog) dialog = $('#ajaxModal .modal-dialog');
        if(position)
        {
           var half = Math.max(0, ($(window).height() - dialog.outerHeight())/2);
           var pos = position == 'fit' ? (half*2/3) : (position == 'center' ? half : position);
           dialog.css('margin-top', pos);
        }
    }

    $.extend({ajustModalPosition: ajustModalPosition, modalTrigger: showIframeModal, colorbox: function(setting)
    {
        if((typeof setting == 'object') && setting.iframe)
        {
            $.modalTrigger({type: 'iframe', width: setting['width'], afterHide: setting['onCleanup'], url: setting['href']});
        }
    }});

    $('[data-toggle=modal], a.iframe').modalTrigger();

    jQuery.fn.colorbox = function(setting)
    {
        if((typeof setting == 'object') && setting.iframe)
        {
            $(this).modalTrigger({type: 'iframe', width: setting['width'], afterHide: setting['onCleanup'], url: setting['href']});
        }
    }
}

/**
 * Set modal for list page.
 *
 * Open operation pages in modal for list pages, after the modal window close, reload the list content and repace the replaceID.
 * 
 * @param string   triggerClass   the class for colorbox binding.
 * @param string   replaceID       the html object to be replaced.
 * @access public
 * @return void
 */
function setModal4List(triggerClass, replaceID, callback, width)
{
    if(typeof(width) == 'undefined') width = 900;
    $('.' + triggerClass).modalTrigger(
    {
        width: width,
        type: 'iframe',

        afterHide:function()
        {
            var selfClose = $.cookie('selfClose');
            if(selfClose != 1) return;
            saveWindowSize();

            if(typeof(replaceID) == 'string' && replaceID.length > 0)
            {
                $.cancelReloadCloseModal();

                var link = self.location.href;
                var idQuery = '#' + replaceID;
                $(idQuery).wrap("<div id='tmpDiv'></div>");
                $('#tmpDiv').load(link + ' ' + idQuery, function()
                {
                    $('#tmpDiv').replaceWith($('#tmpDiv').html());
                    setTimeout(function(){setModal4List(triggerClass, replaceID, callback, width);},150);

                    var $list = $(idQuery), $datatable = $('#datatable-' + $list.attr('id'));
                    if($list.hasClass('datatable') && $datatable.length && $.fn.datatable)
                    {
                        $list.hide();
                        $datatable.data('zui.datatable').load(idQuery);
                    }

                    $list.find('tbody tr:not(.active-disabled) td').click(function(){$(this).closest('tr').toggleClass('active');});
                    $list.find('[data-toggle=modal], a.iframe').modalTrigger();
                    try
                    {
                        $(".date").datetimepicker(datepickerOptions);
                    }
                    catch(err){}
                    if(typeof(e) == 'function') callback();
                    $.cookie('selfClose', 0);
                });
            }
        }
    });
}

/**
 * Set table behavior
 * 
 * @access public
 * @return void
 */
function setTableBehavior()
{
    $('#wrap .table:not(.table-data, .table-form, .active-disabled)').on('click', 'tbody tr:not(.active-disabled) td', function(){$(this).closest('tr').toggleClass('active');});
    $('#wrap .outer > .table, #wrap .outer > form > .table, #wrap .outer > .mian > .table, #wrap .outer > .mian > form > .table, #wrap .outer > .container > .table').not('.table-data, .table-form, .table-custom').addClass('table table-condensed table-hover table-striped tablesorter');
}

/**
 * Fix style
 * 
 * @access public
 * @return void
 */
function fixStyle()
{
    var $actions = $('#titlebar > .actions');
    if($actions.length) $('#titlebar > .heading').css('padding-right', $actions.width());
}

/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function() 
{
    $('body').addClass('m-{currentModule}-{currentMethod}'.format(config));

    setModal();
    setTableBehavior();
    setForm();
    saveWindowSize();
    setSearchBox();
    setOuterBox();

    setRequiredFields();
    setPlaceholder();

    setModalTriggerLink();

    autoCheck();
    toggleSearch();

    fixStyle();

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
