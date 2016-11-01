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
    if(config.requestType != 'GET')
    {
        if(config.requestType == 'PATH_INFO')  link = config.webRoot + moduleName + config.requestFix + methodName;
        if(config.requestType == 'PATH_INFO2') link = config.webRoot + 'index.php/'  + moduleName + config.requestFix + methodName;
        if(vars)
        {
            for(i = 0; i < vars.length; i ++) link += config.requestFix + vars[i][1];
        }
        link += '.' + viewType;
    }
    else
    {
        link = config.router + '?' + config.moduleVar + '=' + moduleName + '&' + config.methodVar + '=' + methodName + '&' + config.viewVar + '=' + viewType;
        if(vars) for(i = 0; i < vars.length; i ++) link += '&' + vars[i][0] + '=' + vars[i][1];
    }

    /* if page has onlybody param then add this param in all link. the param hide header and footer. */
    if((typeof(config.onlybody) != 'undefined' && config.onlybody == 'yes') || isOnlyBody)
    {
        var onlybody = config.requestType != 'GET' ? "?onlybody=yes" : '&onlybody=yes';
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
    var itemID = objectType == 'branch' ? '#currentBranch' : '#currentItem';
    var li = $(itemID).closest('li');
    if(li.hasClass('show')) {li.removeClass('show'); return;}

    var $dropMenu = li.find('#dropMenu');
    if(!li.data('showagain'))
    {
        li.data('showagain', true);
        $(document).click(function() {li.removeClass('show');});
        li.click(function(e){e.stopPropagation();});

        $dropMenu.on('keydown', '#search', function(e)
        {
            var code = e.which;
            var $this = $dropMenu.find('#searchResult > .search-list > ul > li.active');
            if(code === 38) // up
            {
                $this.removeClass('active');
                if($this.length)
                {
                    var $prev = $this.prev('li');
                    while($prev.length && $prev.hasClass('heading'))
                    {
                        $prev = $prev.prev('li');
                    }
                    if($prev.length)
                    {
                        $prev.addClass('active');
                        return;
                    }
                }
                $dropMenu.find('#searchResult > .search-list > ul > li:not(.heading):last').addClass('active');
            }
            else if(code === 40) // down
            {
                $this.removeClass('active');
                if($this.length)
                {
                    var $next = $this.next('li');
                    while($next.length && $next.hasClass('heading'))
                    {
                        $next = $next.next('li');
                    }
                    if($next.length)
                    {
                        $next.addClass('active');
                        return;
                    }
                }
                $dropMenu.find('#searchResult > .search-list > ul > li:not(.heading):first').addClass('active');
            }
            else if(code === 13) // enter
            {
                if($this.length) window.location.href = $this.children('a').attr('href');
            }
        }).on('change keyup paste input propertychange', '#search', function()
        {
            var $search = $(this);
            var searchKey = $search.val();
            if(searchKey === $dropMenu.data('lastSearchKey')) return;

            clearTimeout($dropMenu.data('lastSearch'));
            $dropMenu.data('lastSearch', setTimeout(function()
            {
                $dropMenu.data('lastSearchKey', searchKey);
                searchItems(searchKey, objectType, objectID, module, method, extra);
            }, 200));
        }).on('mouseenter', '#searchResult .search-list > ul > li', function(){
            $dropMenu.find('#searchResult > .search-list > ul > li.active').removeClass('active');
            $(this).addClass('active');
        });
    }
    $.get(createLink(objectType, 'ajaxGetDropMenu', "objectID=" + objectID + "&module=" + module + "&method=" + method + "&extra=" + extra), function(data)
    {
        $dropMenu.html(data).find('#search').focus();
        $dropMenu.find('#searchResult > .search-list > ul > li:not(.heading)').removeClass('active').first().addClass('active');
    });

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
    var itemID = objectType == 'branch' ? '#currentBranch' : '#currentItem';
    var li     = $(itemID).closest('li');
    var $dropMenu = li.find('#dropMenu');
    $.get(createLink(objectType, 'ajaxGetDropMenu', "objectID=" + objectID + "&module=" + module + "&method=" + method + "&extra=" + extra), function(data)
    {
        $dropMenu.html(data);
        setTimeout(function(){$dropMenu.find("#search").focus();}, 200);
        $dropMenu.find('#searchResult > .search-list > ul > li:not(.heading)').removeClass('active').first().addClass('active');
    });
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
        var itemID = objectType == 'branch' ? '#currentBranch' : '#currentItem';
        var li     = $(itemID).closest('li');
        var $dropMenu = li.find('#dropMenu');
        keywords = encodeURI(keywords);
        if(keywords != '-') $.get(createLink(objectType, 'ajaxGetMatchedItems', "keywords=" + keywords + "&module=" + module + "&method=" + method + "&extra=" + extra + "&objectID=" + objectID), function(data)
        {
            $dropMenu.find('#searchResult').html(data).find('.search-list > ul > li:not(.heading)').removeClass('active').first().addClass('active');
        });
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
            link = createLink(module, method, 'rootID=' + libID);
        }
        else
        {
            link = createLink('doc', 'browse');
        }
    }
    else if(module == 'tree')
    {
        link = createLink(module, method, 'rootID=' + libID + '&type=' + extra);
    }
    location.href = link;
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
    if(config.requiredFields)
    {
        requiredFields = config.requiredFields.split(',');
        for(i = 0; i < requiredFields.length; i++)
        {
            $('#' + requiredFields[i]).closest('td,th').prepend("<div class='required required-wrapper'></div>");
            var colEle = $('#' + requiredFields[i]).closest('[class*="col-"]');
            if(colEle.parent().hasClass('form-group')) colEle.addClass('required');
        }
    }
    $('.required').closest('td,th').next().css('padding-left', '15px');
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
    if(typeof(holders) != 'undefined')
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
//    if($('.sub-featurebar').length) $('#featurebar').addClass('with-sub');

    var side   = $('#wrap .outer > .side');
    var resetOuterHeight = function()
    {
        var sideH  = side.length ? (side.outerHeight() + $('#featurebar').outerHeight() + 20) : 0;
        var height = Math.max(sideH, $(window).height() - $('#header').outerHeight() - ($('#footer').outerHeight() || 0) - 20);
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
function setFormAction(actionLink, hiddenwin, obj)
{
    $form = typeof(obj) == 'undefined' ? $('form') : $(obj).closest('form');
    if(hiddenwin) $form.attr('target', hiddenwin);
    else $form.removeAttr('target');

    $form.attr('action', actionLink).submit();
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
 * Ajax get contacts.
 * 
 * @param  obj $obj 
 * @access public
 * @return void
 */
function ajaxGetContacts(obj)
{
    link = createLink('user', 'ajaxGetContactList');
    $.get(link, function(contacts)
    {
      if(!contacts) return false;

      $inputgroup = $(obj).closest('.input-group');
      $inputgroup.find('.input-group-btn').remove();
      $inputgroup.append(contacts);
      $inputgroup.find('select:last').chosen(defaultChosenOptions);
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
 * Make table checkable by click row
 *
 * @param  $table
 * @access public
 * @return void
 */
function checkTable($table)
{
    $table = $table || $('.tablesorter:not(.table-datatable)');

    var checkRow = function(checked)
    {
        if(document.activeElement.type != 'select-one' && document.activeElement.type != 'text')
        {
            var $this = $(this);
            var $tr = $this.closest('tr');
            var $checkbox = $tr.find(':checkbox');
            if($checkbox.size() == 0) return;

            var isChecked = $checkbox.prop('checked');
            if(!$this.is(':checkbox'))
            {
                isChecked = checked === true || checked === false  ? checked : !isChecked;
                $checkbox.prop('checked', isChecked);
            }
            if(!$tr.hasClass('.active-disabled')) {
                $tr.toggleClass('active', isChecked);
            }
            $tr.closest('.table').find('.rows-selector').prop('checked', false);
        }
    };

    $table.selectable(
    {
        selector: 'tbody > tr',
        trigger: 'tbody',
        ignoreVal: 10,
        start: function(e)
        {
            if($(e.target).is(':checkbox,a')) return false;
            var that = this;
            that.selections = {};
            that.$.find('tbody > tr').each(function(idx)
            {
                var $tr = $(this);
                if($tr.hasClass(that.options.selectClass))
                {
                    that.selections[$tr.data('id')] = idx + 1;
                }
            });
        },
        clickBehavior: 'multi',
        select: function(e)
        {
            checkRow.call(e.target, true);
        },
        unselect: function(e)
        {
            checkRow.call(e.target, false);
        }
    }).on('click', 'tbody > tr :checkbox', function(e){checkRow.call(this); e.stopPropagation();}).on('click mousedown mousemove mouseup', 'tbody a,tbody select,tbody input', function(e) {e.stopPropagation();});

    $(document).off('change.checktable').on('change.checktable', '.rows-selector:checkbox', function()
    {
        var $checkbox = $(this);
        var $datatable = $checkbox.closest('.datatable');
        if($datatable.length) {
            var $checkAll = $datatable.find('.check-all.check-btn:first').trigger('click');
            $checkbox.prop('checked', $checkAll.hasClass('checked'))
            return;
        }
        var scope = $checkbox.data('scope');
        var $target = scope ? $('#' + scope) : $checkbox.closest('.table');
        var isChecked = $checkbox.prop('checked');
        $target.find('tbody > tr').toggleClass('active', isChecked).find('td :checkbox').prop('checked', isChecked);
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
function ajaxGetSearchForm(querybox)
{
    var $querybox = $(querybox || '#querybox');
    if($querybox.html() == '')
    {
        $.get(createLink('search', 'buildForm'), function(data)
        {
            $querybox.html(data);
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
                    $.get(document.location.href, function(data)
                    {
                        $('#' + replaceID).html($(data).find('#' + replaceID).html());
                        if(typeof sortTable == 'function') sortTable(); 
                        $('#' + replaceID).find('[data-toggle=modal], a.iframe').modalTrigger();
                        $('#' + replaceID).find('table.datatable').datatable();
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
                var type = (setting ? setting.type : false) || ($e.hasClass('iframe') ? 'iframe' : ($e.data('type') || 'ajax'));
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
                        var options = 
                        {
                            width:      $e.data('width') || 800,
                            icon:       $e.data('icon') || '?',
                            title:      $e.data('title') || $e.attr('title') || $e.text(),
                        };
                        var ajaxModal = $('#ajaxModal');
                        if(data.indexOf('modal-dialog') < 0)
                        {
                            data = "<div class='modal-dialog modal-ajax' style='width: {width};'><div class='modal-content'><div class='modal-header'><button class='close' data-dismiss='modal'>×</button><h4 class='modal-title'><i class='icon-{icon}'></i> {title}</h4></div><div class='modal-body' style='height:{height}'>{content}</div></div></div>".format($.extend({content: data}, options));
                        }
                        ajaxModal.html(data);

                        /* Set the width of modal dialog. */
                        if(options.width)
                        {
                            var modalWidth = parseInt(options.width);
                            $ajaxModal.data('width', modalWidth).find('.modal-dialog').css('width', modalWidth);
                            ajustModalPosition();
                        }

                        ajaxModal.modal('show');
                    });
                }

                /* Save the href to rel attribute thus we can save it. */
                $('#ajaxModal').attr('rel', url);

                event.preventDefault();
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

                    $list.find('[data-toggle=modal], a.iframe').modalTrigger();
                    try
                    {
                        $('.date').datetimepicker(datepickerOptions);
                    }
                    catch(err){}

                    if($list.is('.tablesorter:not(.table-datatable)')) checkTable($list);
                    else $list.find('tbody tr:not(.active-disabled) td').click(function(){$(this).closest('tr').toggleClass('active');});

                    if($.isFunction(callback)) callback();
                    $.cookie('selfClose', 0);
                });
            }
            else if($.isFunction(callback)) callback();
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
    $('#wrap .outer > .table, #wrap .outer > form > .table, #wrap .outer > .mian > .table, #wrap .outer > .mian > form > .table, #wrap .outer > .container > .table').not('.table-data, .table-form, .table-custom').addClass('table table-condensed table-hover table-striped tablesorter');

    $(document).on('click', 'tr[data-url]', function()
    {
        var url = $(this).data('url');
        if(url) window.location.href = url;
    });
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

/**
 * Start cron.
 * 
 * @access public
 * @return void
 */
function startCron()
{
    $.ajax({type:"GET", timeout:100, url:createLink('cron', 'ajaxExec')});
}

function computePasswordStrength(password)
{
    if(password.length == 0) return 0;

    var strength = 0;
    var length   = password.length;

    var uniqueChars = '';
    var complexity  = new Array();
    for(i = 0; i < length; i++)
    {
        letter = password.charAt(i);
        var asc = letter.charCodeAt();
        if(asc >= 48 && asc <= 57)
        {
            complexity[2] = 2;
        }
        else if((asc >= 65 && asc <= 90))
        {
            complexity[1] = 2;
        }
        else if(asc >= 97 && asc <= 122)
        {
            complexity[0] = 1;
        }
        else
        {
            complexity[3] = 3;
        }
        if(uniqueChars.indexOf(letter) == -1) uniqueChars += letter;
    }

    if(uniqueChars.length > 4) strength += uniqueChars.length - 4;
    var sumComplexity = 0;
    var complexitySize = 0;
    for(i in complexity)
    {
        complexitySize += 1;
        sumComplexity += complexity[i];
    }
    strength += sumComplexity + (2 * (complexitySize - 1));
    if(length < 6 && strength >= 10) strength = 9;

    strength = strength > 29 ? 29 : strength;
    strength = Math.floor(strength / 10);

    return strength;
}

/**
 * Check onlybody page when it is not open in modal then location to on onlybody page. 
 * 
 * @access public
 * @return void
 */
function checkOnlybodyPage()
{
    if(location.href == top.location.href)
    {
        href = location.href.replace('?onlybody=yes', '');
        location.href = href.replace('&onlybody=yes', '');
    }
}

/**
 * Fixed tfoot action like productplan,release.
 * 
 * @param  string $formID 
 * @access public
 * @return void
 */
function fixedTfootAction(formID)
{
    if($(formID).size() == 0) return false;
    if($(formID).find('table:last').find('tfoot').size() == 0) return false;

    fixTfootInit();
    $(window).scroll(fixTfoot);//Fix table foot when scrolling.
    $('.side-handle').click(function(){setTimeout(fixTfootInit, 300);});//Fix table foot if module tree is hidden or displayed.

    var $table, $tfoot, $inputGroup, tableWidth, tableOffset, hasFixed;
    function fixTfoot()
    {
        $table       = $(formID).find('table:last');
        $tfoot       = $table.find('tfoot');
        tableWidth   = $table.width();
        hasFixed     = $tfoot.hasClass('fixedTfootAction');
        offsetHeight = $(window).height() + $(window).scrollTop();
        tableOffset  = $table.offset().top + $table.height() - $tfoot.height() / 5;
        $inputGroup  = $tfoot.find('.table-actions').children('.input-group');

        if(!hasFixed && offsetHeight <= tableOffset)
        {
            $tfoot.addClass('fixedTfootAction');
            $tfoot.width(tableWidth);
            $tfoot.find('td').width(tableWidth);
            if($inputGroup.size() > 0) $inputGroup.width($inputGroup.width());
        }
        if(hasFixed && (offsetHeight > tableOffset || $(document).height() == offsetHeight))
        {
            $tfoot.removeClass('fixedTfootAction');
            $tfoot.removeAttr('style');
            $tfoot.find('td').removeAttr('style');
        }
    }
    function fixTfootInit()
    {
        $tfoot = $(formID).find('table:last').find('tfoot')
        if($tfoot.hasClass('fixedTfootAction')) $tfoot.removeClass('fixedTfootAction');
        fixTfoot();
    }
}

/**
 * Fixed table head in div box.
 * 
 * @param  string $boxObj 
 * @access public
 * @return void
 */
function fixedTableHead(boxObj)
{
    $(boxObj).scroll(function()
    {
        var hasFixed  = $(this).find('.fixedHead').size() > 0;
        if(!hasFixed)
        {
            $(this).css('position', 'relative');
            if($(this).find('table').size() == 1)
            {
                var fixed = "<table class='fixedHead' style='position:absolute;top:0px'><thead>" + $(this).find('table thead').html() + '</thead></table>';
                $(this).prepend(fixed);
                var $fixTable = $(this).find('table.fixedHead');
                $fixTable.addClass($(this).find('table:last').attr('class'));
                var $dataTable = $(this).find('table:last thead th');
                $fixTable.find('thead th').each(function(i){$fixTable.find('thead th').eq(i).width($dataTable.eq(i).width());})
            }
        }
        $(this).find('table.fixedHead').css('top',$(this).scrollTop());
    });
}

/**
 * Fixed table head in list when scrolling.
 * 
 * @param  string $tableID 
 * @access public
 * @return void
 */
function fixedTheadOfList(tableID)
{
    if($(tableID).size() == 0) return false;
    if($(tableID).css('display') == 'none') return false;
    if($(tableID).find('thead').size() == 0) return false;

    fixTheadInit();
    $(window).scroll(fixThead);//Fix table head when scrolling.
    $('.side-handle').click(function(){setTimeout(fixTheadInit, 300);});//Fix table head if module tree is hidden or displayed.

    var tableWidth, theadOffset, fixedThead, $fixedThead;
    function fixThead()
    {
        theadOffset = $(tableID).find('thead').offset().top;
        $fixedThead = $(tableID).parent().find('.fixedTheadOfList');
        if($fixedThead.size() <= 0 &&theadOffset < $(window).scrollTop())
        {
            tableWidth  = $(tableID).width();
            fixedThead  = "<table class='fixedTheadOfList'><thead>" + $(tableID).find('thead').html() + '</thead></table>';
            $(tableID).before(fixedThead);
            $('.fixedTheadOfList').addClass($(tableID).attr('class')).width(tableWidth);
        }
        if($fixedThead.size() > 0 && theadOffset >= $(window).scrollTop()) $fixedThead.remove();
    }
    function fixTheadInit()
    {
        $fixedThead = $(tableID).parent().find('.fixedTheadOfList');
        if($fixedThead.size() > 0) $fixedThead.remove();
        fixThead();
    }
}

/**
 * Init prioprity selectors
 * @return void
 */
function initPrioritySelector()
{
    $('.dropdown-pris').each(function()
    {
        var $dropdown = $(this);
        var prefix = $dropdown.data('prefix') || 'pri';
        var $select = $dropdown.find('select');
        var selectVal = parseInt($select.hide().val());
        var $menu = $dropdown.children('.dropdown-menu');
        if(!$menu.length)
        {
            $menu = $('<ul class="dropdown-menu"></ul>');
            $dropdown.append($menu);
        }
        if(!$menu.children('li').length)
        {
            var set = $select.children('option').map(function() {return parseInt($(this).val());}).get();
            if(!set || !set.length)
            {
                set = $dropdown.data('set');
                set = set ? set.split(',') : [0,1,2,3,4];
            }
            set.sort(function(a,b){return a - b});
            for(var i = 0; i < set.length; ++i)
            {
                var v = set[i];
                $menu.append('<li><a href="###" data-pri="' + v + '"><span class="' + prefix + v + '">' + (v ? v : '') + '</span></a></li>');
            }
        }
        $menu.find('a[data-pri="' + selectVal + '"]').parent().addClass('active');
        $dropdown.find('.pri-text').html('<span class="' + prefix + selectVal + '">' + (selectVal ? selectVal : '') + '</span>');

        $dropdown.on('click', '.dropdown-menu > li > a', function()
        {
            var $a = $(this);
            $menu.children('li.active').removeClass('active');
            $a.parent().addClass('active');
            selectVal = $a.data('pri');
            $select.val(selectVal);
            $dropdown.find('.pri-text').html('<span class="' + prefix + selectVal + '">' + (selectVal ? selectVal : '') + '</span>');
        });
    });
}

/**
 * Apply cs style to page
 * @return void
 */
function applyCssStyle(css, tag)
{
    tag = tag || 'default';
    var name = 'applyStyle-' + tag;
    var $style = $('style#' + name);
    if(!$style.length)
    {
        $style = $('<style id="' + name + '">').appendTo('body');
    }
    var styleTag = $style.get(0);
    if (styleTag.styleSheet) styleTag.styleSheet.cssText = css;
    else styleTag.innerHTML = css;
}

/**
 * Show browser notice 
 * 
 * @access public
 * @return void
 */
function showBrowserNotice()
{
    userAgent = navigator.userAgent.toLowerCase();
    $browser  = new Object();
    $browser.msie   = /msie/.test(userAgent);
    $browser.chrome = /chrome/.test(userAgent);

    //if($browser.msie)
    //{
    //    match = /(msie) ([\w.]+)/.exec(userAgent);
    //    $browser.version = match[2] || '0';
    //}

    var show = false;

    /* IE 6,7. */
    //if($browser.msie && $browser.version <= 7) show = true;

    /* Souhu */
    if(navigator.userAgent.indexOf('MetaSr') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('LBBROWSER') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('QQBrowser') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('TheWorld') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('BIDUBrowser') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('Maxthon') >= 0)
    {
        show = true;
    }
    /* 360. */
    //else if($browser.chrome && !(window.clientInformation && window.clientInformation.mediaDevices))
    //{
    //    show = true;
    //}

    if(show) $('body').prepend('<div class="alert alert-info alert-dismissable" style="margin:0px;"><button type=button" onclick="ajaxIgnoreBrowser()" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-remove"></i></button><p>' + browserNotice + '</p></div>');
}

/**
 * Remove cookie by key
 * 
 * @param  cookieKey $cookieKey 
 * @access public
 * @return void
 */
function removeCookieByKey(cookieKey)
{
    $.cookie(cookieKey, '', {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}

/**
 * Bind hotkey event
 * @access public
 * @return void
 */
function initHotKey()
{
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
}

/**
 * Init help link for user to open zentao help website in iframe
 * @access public
 * @return void
 */
function initHelpLink()
{
    var zentaoUrl = 'http://www.zentao.net/book/zentaopmshelp.html?fullScreen=zentao';
    var $mainNav = $('#mainmenu > .nav').first();
    var showLoadingError;
    var timeout = 10000;

    var clearLoadingError = function()
    {
        clearTimeout(showLoadingError);
        $('#helpContent').removeClass('show-error');
    };

    var openHelp = function()
    {
        clearLoadingError();
        var $oldActiveItem = $mainNav.children('li.active:not(#helpMenuItem)').removeClass('active').addClass('close-help-tab');
        var $helpMenuItem = $('#helpMenuItem').addClass('active');
        var $help = $('#helpContent');
        if(!$help.length)
        {
            $help = $('<div id="helpContent"><div class="load-error text-center"><h4 class="text-danger">' + lang.timeout + '</h4><p><a href="###" class="open-help-tab"><i class="icon icon-arrow-right"></i> ' + zentaoUrl + '</a></p></div><iframe id="helpIframe" name="helpIframe" src="' + zentaoUrl + '" frameborder="no" allowtransparency="true" scrolling="auto" hidefocus="" style="width: 100%; height: 100%; left: 0px;"></iframe></div>');
            $('#header').after($help);
            var frame = $('#helpIframe').get(0);
            showLoadingError = setTimeout(function()
            {
                $('#helpContent').addClass('show-error');
            }, timeout);
            frame.onload = frame.onreadystatechange = function()
            {
                if(this.readyState && this.readyState != 'complete') return;
                clearLoadingError();
            }
        } else if($('body').hasClass('show-help-tab'))
        {
            $('#helpIframe').get(0).contentWindow.location.replace(zentaoUrl);
            return;
        }
        $('body').addClass('show-help-tab');
    };

    var closeHelp = function()
    {
        $('body').removeClass('show-help-tab');
        $('#helpMenuItem').removeClass('active');
        $mainNav.find('li.close-help-tab').removeClass('close-help-tab').addClass('active');
    };

    $(document).on('click', '.open-help-tab', function()
    {
        var $helpMenuItem = $('#helpMenuItem');
        if(!$helpMenuItem.length)
        {
            $helpMenuItem = $('<li id="helpMenuItem"><a href="javascript:;" class="open-help-tab">' + $(this).text() + '<i class="icon icon-remove close-help-tab"></i></a></li>');
            $mainNav.find('.custom-item').before($helpMenuItem);
        }
        openHelp();
    }).on('click', '.close-help-tab', function(e)
    {
        closeHelp();
        e.stopPropagation();
        e.preventDefault();
    });
}

/**
 * Set homepage.
 * 
 * @param  string $module 
 * @param  string $page 
 * @access public
 * @return void
 */
function setHomepage(module, page)
{
    $.get(createLink('custom', 'ajaxSetHomepage', 'module=' + module + '&page=' + page), function(){location.reload(true)});
}

/**
 * Reload page when tutorial mode setted in this session but not load in iframe
 * 
 * @access public
 * @return void
 */
function checkTutorial()
{
    if(config.currentModule != 'tutorial' && window.TUTORIAL && (!frameElement || frameElement.tagName != 'IFRAME'))
    {
        if(confirm(window.TUTORIAL.tip))
        {
            $.getJSON(createLink('tutorial', 'ajaxQuit'), function()
            {
                window.location.reload();
            }).error(function(){alert(lang.timeout)});
        }
    }
}

/* Remove 'ditto' in first row when batch create or edit. */
function removeDitto()
{
    $firstTr = $('.table-form').find('tbody tr:first');
    $firstTr.find('td select').each(function()
    {
        $(this).find("option[value='ditto']").remove();
        $(this).trigger("chosen:updated");
    });
}

/**
 * Revert module cookie.
 * 
 * @access public
 * @return void
 */
function revertModuleCookie()
{
    if($('#mainmenu .nav li[data-id="project"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="task"] a').click(function()
        {
            $.cookie('moduleBrowseParam', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
    if($('#mainmenu .nav li[data-id="product"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="story"] a').click(function()
        {
            $.cookie('storyModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
    if($('#mainmenu .nav li[data-id="qa"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="bug"] a').click(function()
        {
            $.cookie('bugModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
        $('#modulemenu .nav li[data-id="testcase"] a').click(function()
        {
            $.cookie('caseModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
}

/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function() 
{
    if(typeof(config.onlybody) != 'undefined' && config.onlybody == 'yes') checkOnlybodyPage();
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

    checkTable();
    toggleSearch();

    fixStyle();

    // Init tree menu
    $('.tree').tree({name: config.currentModule + '-' + config.currentMethod, initialState: 'preserve'});

    $(window).resize(saveWindowSize);   // When window resized, call it again.

    if(needPing) setTimeout('setPing()', 1000 * 60 * 10);  // After 10 minutes, begin ping.

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

    initPrioritySelector();
    initHotKey();
    initHelpLink();
    checkTutorial();
    revertModuleCookie();
});
