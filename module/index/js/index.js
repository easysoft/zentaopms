(function()
{
    /* Init variables */
    var openedTabs      = {}; // Key-value to save group-tab pairs
    var groupsMap       = {}; // Key-value to save group-menu pairs
    var openedTabZIndex = 10; // Last opened tab z-index
    var defaultTabGroup;      // Default tab group
    var lastOpenedGroup;      // Last opened tab group

    /**
     * Init menu list
     */
    function initMenuList()
    {
        var $helpLink = $('#helpLink');
        groupsMap.help =
        {
            group:    'help',
            icon:     'icon-help',
            url:      $helpLink.attr('href'),
            external: true,
            text:     $helpLink.text(),
            pageUrl:  config.webRoot + '#open=help'
        };
        var $menuMainNav = $('#menuMainNav').empty();
        window.menuItems.forEach(function(item)
        {
            if(item === 'divider') return $menuMainNav.append('<li class="divider"></li>');

            var $link= $('<a data-pos="menu"></a>')
                .attr('data-group', item.group)
                .attr('class', 'show-in-tab')
                .html(item.title);

            item.icon = ($link.find('.icon').attr('class') || '').replace('icon ', '');
            item.text = $link.text().trim();
            $link.html('<i class="icon ' + item.icon + '"></i><span class="text">' + item.text + '</span>');
            groupsMap[item.group] = item;

            $('<li></li>').attr('data-group', item.group)
                .attr('title', item.text)
                .append($link)
                .appendTo($menuMainNav);

            if(!defaultTabGroup) defaultTabGroup = item.group;
        });
    }

    /**
     * Get tab group from url
     * @param {String} urlOrModuleName Url string
     *
     */
    function getGroupFromUrl(urlOrModuleName)
    {
        var group = window.navGroup[urlOrModuleName];
        if(group) return group;

        var link = $.parseLink(urlOrModuleName);
        if(!link.moduleName || link.isOnlyBody) return '';

        if(link.hash && link.hash.indexOf('open=') === 0) return link.hash.substr(5);

        /* Handling special situations */
        var moduleName      = link.moduleName;
        var methodName      = link.methodName;
        var methodLowerCase = methodName.toLowerCase();
        if(moduleName === 'doc')
        {
            if(link.prj) return 'project';

            if(methodLowerCase === 'objectlibs' && (link.params.from || link.params.$3) == 'product') return 'product';
            if(methodLowerCase === 'showfiles'  && (link.params.from || link.params.$3) == 'product') return 'product';
            if(methodLowerCase === 'browse'     && (link.params.from || link.params.$5) == 'product') return 'product';
            if(methodLowerCase === 'view'       && (link.params.from || link.params.$3) == 'product') return 'product';
            if(methodLowerCase === 'edit'       && (link.params.from || link.params.$3) == 'product') return 'product';
            if(methodLowerCase === 'delete'     && (link.params.from || link.params.$3) == 'product') return 'product';
            if(methodLowerCase === 'create'     && (link.params.from || link.params.$4) == 'product') return 'product';
            return 'doc';
        }
        if(moduleName === 'custom' && ['estimate', 'browsestoryconcept', 'configurescrum'].includes(methodLowerCase))
        {
            return 'system';
        }
        if(moduleName === 'program')
        {
            if(methodLowerCase.indexOf('pgm') === 0) return 'program';
            if(methodLowerCase === 'index' || methodLowerCase.indexOf('prj') === 0) return 'project';
        }
        if(moduleName === 'story' && (methodLowerCase === 'zerocase' || (methodLowerCase === 'batchedit' && (link.params.projectID || link.params.$2))))
        {
            return 'project';
        }
        if(['repo', 'jenkins', 'job', 'compile'].includes(moduleName))
        {
            return link.prj ? 'project' : 'repo';
        }
        if(moduleName === 'product')
        {
            if(methodLowerCase === 'create' && (link.params.programID || link.params.$1)) return 'program';
            if(methodLowerCase === 'edit' && (link.params.programID || link.params.$4)) return 'program';
            if(methodLowerCase === 'batchedit') return 'program';
            if(methodLowerCase === 'showerrornone' && (link.params.fromModule || link.params.$1) !== 'product') return 'project';
        }
        if(moduleName === 'stakeholder')
        {
            if(methodLowerCase === 'create' && (link.params.programID || link.params.$1)) return 'program';
        }
        if(moduleName === 'tree')
        {
            if(methodLowerCase === 'browse')
            {
                var viewType = link.params.viewType || link.params.$2;
                if(['bug', 'case', 'caselib'].includes(viewType)) return 'project';

                if(viewType === 'doc' && (link.params.from === 'product' || link.params.$5 == 'product')) return 'product';
                if(viewType === 'doc' && (link.params.from === 'project' || link.params.$5 == 'project')) return 'project';
                if(viewType === 'doc') return 'doc';
            }
            else if(methodLowerCase === 'browsetask')
            {
                return 'project';
            }
        }

        var myMethods = 'todocalendar|effortcalendar|todo|task|story|bug|testtask|testcase|execution|issue|risk|dynamic|profile';
        if(moduleName === 'user' && myMethods.indexOf(methodLowerCase) != -1) return 'my';

        group = window.navGroup[moduleName] || moduleName || urlOrModuleName;
        return groupsMap[group] ? group : '';
    }

    /**
     * Open tab
     * @param {string} [url]   Url to open in tab
     * @param {string} [group] The group of target tab to open
     * @return {void}
     */
    function openTab(url, group)
    {
        /* Check params */
        if(!group)
        {
            if(groupsMap[url])
            {
                group = url;
                url = '';
            }
            else
            {
                group = getGroupFromUrl(url);
                if(!group) return false;
            }
        }

        /* Highlight at main menu */
        var $menuMainNav = $('#menuMainNav');
        var $lastActiveNav = $menuMainNav.find('li.active');
        if($lastActiveNav.data('group') !== group)
        {
            $lastActiveNav.removeClass('active');
            $menuMainNav.find('li[data-group="' + group + '"]').addClass('active');
        }

        /* Create pate tab object and store it */
        var tab = openedTabs[group];
        if(!tab)
        {
            var $iframe = $(
            [
                '<iframe',
                    'id="tabIframe-' + group + '"',
                    'name="tab-' + group + '"',
                    'frameborder="no"',
                    'allowtransparency="true"',
                    'scrolling="auto"',
                    'style="width: 100%; height: 100%; left: 0px;"',
                '/>'
            ].join(' '));
            var $page = $('<div class="page-tab" id="tab-' + group + '"></div>')
                .append($iframe)
                .appendTo('#pages');

            tab = $.extend({$iframe: $iframe, $page: $page, group: group}, groupsMap[group]);
            openedTabs[group] = tab;

            /* If first show without url, then use the default url */
            if(!url) url = groupsMap[group].url;
        }

        /* Show page tab and update iframe source */
        if(url) reloadTab(group, url);
        tab.zIndex = openedTabZIndex++;
        tab.$page.show().css('z-index', tab.zIndex);

        /* Update task bar */
        var $bars = $('#bars');
        var $bar = $('#tabBar-' + group);
        if(!$bar.length)
        {
            var $link= $('<a data-pos="bar"></a>')
                .attr('data-group', group)
                .attr('class', 'show-in-tab')
                .html(tab.text);
            $bar = $('<li></li>').attr('data-group', group)
                .attr('id', 'tabBar-' + group)
                .append($link)
                .appendTo($bars);
        }
        var $lastActiveBar = $bars.find('li.active');
        if($lastActiveBar.data('group') !== group)
        {
            $lastActiveBar.removeClass('active');
            $bars.find('li[data-group="' + group + '"]').addClass('active');
        }
        tab.$bar = $bar;

        /* Update tab state */
        tab.show = true;
        if(lastOpenedGroup !== group)
        {
            lastOpenedGroup = group;
            updateTabUrl(group);
        }

        return true;
    }

    /**
     * Get last opened tab
     * @param {boolean} [onlyShowed] If set to true then only get last tab from tabs are showed
     * @returns {object} The opened tab info object
     */
    function getLastTab(onlyShowed)
    {
        var lastShowIndex = 0;
        var lastTab = null;
        for(var group in openedTabs)
        {
            var tab = openedTabs[group];
            if((!onlyShowed || tab.show) && lastShowIndex < tab.zIndex && !tab.closed)
            {
                lastShowIndex = tab.zIndex;
                lastTab = tab;
            }
        }
        return lastTab;
    }

    /**
     * Hide tab
     * @param {string} group The group of target tab to hide
     * @return {void}
     */
    function hideTab(group)
    {
        var tab = openedTabs[group];
        if(!tab || !tab.show) return;

        tab.$page.hide();
        tab.show = false;
        lastOpenedGroup = null;

        /* Active last tab */
        var lastTab = getLastTab(true) || getLastTab();
        showTab(lastTab ? lastTab.group : defaultTabGroup);
    }

    /**
     * Show tab
     * @param {string} group The group of target tab to show
     * @return {void}
     */
    function showTab(group)
    {
        return openTab('', group);
    }

    /**
     * Toggle tab
     * @param {string} group The group of target tab to toggle
     * @return {void}
     */
    function toggleTab(group)
    {
        var tab = openedTabs[group];
        if(!tab || tab.group !== lastOpenedGroup) showTab(group);
        else hideTab(group);
    }

    /**
     * Close tab
     * @param {string} group The group of target tab to close
     */
    function closeTab(group)
    {
        group = group || lastOpenedGroup;
        var tab = openedTabs[group];
        if(!tab) return;

        tab.closed = true;
        hideTab(group);
        tab.$page.remove();
        tab.$bar.remove();
        delete openedTabs[group];
    }

    /**
     * Reload tab
     * @param {string} group         The group of target tab to reload
     * @param {string|boolean} [url] The new url to load, it's optional
     * @return {void}
     */
    function reloadTab(group, url)
    {
        var tab = openedTabs[group];
        if(!tab) return;

        if(url === true) url = tab.url;
        var iframe = tab.$iframe[0];

        try
        {
            if(url) iframe.contentWindow.location.assign(url);
            else iframe.contentWindow.location.reload(true);
        }
        catch(_)
        {
            iframe.src = url || tab.url;
        }
    }

    /**
     * Update browser url and title for the given tab
     * @param {string} group           The group of target tab to update url
     * @param {string|boolean} [url]   The new url of the tab
     * @param {string|boolean} [title] The new title of the tab
     * @return {void}
     */
    function updateTabUrl(group, url, title)
    {
        var tab = openedTabs[group];
        if(!tab) return;

        if(url) tab.pageUrl = url;
        else url = tab.pageUrl;
        if(title) tab.pageTitle = title;
        else title = tab.pageTitle || tab.text;

        if(url && url.indexOf('#') < 0) url = url + '#open=' + group;
        if(lastOpenedGroup === group)
        {
            if(location.url !== url) history.replaceState({}, title, url);
            document.title = title;
        }
    }

    /* Bind helper methods to global object "$.tabs" */
    $.tabs = window.tabs =
    {
        show:       showTab,
        open:       openTab,
        hide:       hideTab,
        toggle:     toggleTab,
        close:      closeTab,
        reload:     reloadTab,
        updateUrl:  updateTabUrl,
        getGroup:   getGroupFromUrl,
        getLastTab: getLastTab,
        openedTabs: openedTabs,
        groupsMap:  groupsMap
    };

    /* Init after current page load */
    $(function()
    {
        initMenuList();

        /* Bind events */
        $(document).on('click', '.open-in-tab,.show-in-tab', function(e)
        {
            var $link = $(this);
            if($link.is('[data-modal],[data-toggle],[data-tab],.iframe,.not-in-tab')) return;
            var url = $link.hasClass('show-in-tab') ? '' : ($link.attr('href') || $link.data('url'));
            if(url && url.indexOf('onlybody=yes') > 0) return;
            if(openTab(url, $link.data('group')))
            {
                e.preventDefault();
                if($link.closest('#userNav').length)
                {
                    var $menu = $('#userNav .dropdown-menu').addClass('hidden');
                    setTimeout(function(){$menu.removeClass('hidden')}, 200);
                }
            }
        }).on('contextmenu', '.open-in-tab,.show-in-tab', function(event)
        {
            var $btn  = $(this);
            var group = $btn.data('group');
            if(!group) return;

            var lang  = window.tabsLang;
            var tab   = openedTabs[group];
            var items = [{label: lang.open, disabled: tab && lastOpenedGroup === group, onClick: function(){showTab(group)}}];
            if(tab)
            {
                items.push({label: lang.reload, onClick: function(){reloadTab(group)}});
                if(group !== 'my') items.push({label: lang.close, onClick: function(){closeTab(group)}});
            }

            var options = {event: event, onClickItem: function(_item, _$item, e){e.preventDefault();}};
            var pos = $btn.data('pos');
            if(pos)
            {
                var bounding = $btn.closest('li')[0].getBoundingClientRect();
                if(pos === 'bar')
                {
                    options.x = bounding.left;
                    options.y = bounding.top - (group === 'my' ? 65 : 92);
                }
                else
                {
                    options.x = bounding.right - 10;
                    options.y = bounding.top;
                }
            }
            $.zui.ContextMenu.show(items, options);
            event.preventDefault();
        });

        /* Redirect or open default tab after document load */
        var defaultOpenUrl = window.defaultOpen;
        if(!defaultOpenUrl && location.hash.indexOf('#open=') === 0)
        {
            defaultOpenUrl = decodeURIComponent(location.hash.substr(6));
        }
        if(defaultOpenUrl) openTab(defaultOpenUrl);
        else openTab(defaultTabGroup);
    });
}());

(function()
{
    $.toggleMenu = function(toggle)
    {
        var $body = $('body');
        if (toggle === undefined) toggle = $body.hasClass('menu-hide');
        $body.toggleClass('menu-hide', !toggle);
        $.cookie('hideMenu', String(!toggle), {expires: config.cookieLife, path: config.webRoot});
    };

    $(function()
    {
        /* Click to show more. */
        $(document).on('click', '.menu-toggle', function()
        {
            $.toggleMenu();
            var $menu = $('#userNav .dropdown-menu').addClass('hidden');
            setTimeout(function(){$menu.removeClass('hidden')}, 200);
        });

        /* Hide execution list on mouseleave or click */
        $(document).click(function()
        {
            $("#moreExecution").hide();
        });

        $("#recentMenu").click(function(event)
        {
            event.stopPropagation();
            getExecutions();
        });

        $("#moreExecution").click(function(event)
        {
            event.stopPropagation();
        });
    });
}());

/* Get recent executions. */
function getExecutions()
{
    var $moreExecution = $('#moreExecution').toggle();
    if(!$moreExecution.is(':hidden'))
    {
        if($('body').hasClass('menu-hide'))
        {
            $('#moreExecution').addClass('more-execution-hide');
        }
        else
        {
            $('#moreExecution').removeClass('more-execution-hide');
        }

        $.ajax(
        {
            url: createLink('project', 'ajaxGetRecentExecutions'),
            dataType: 'html',
            type: 'post',
            success: function(data)
            {
                $('#executionList').html(data);
            }
        })
    }
}

$.extend(
{
    gotoObject:function()
    {
        objectType  = $('#searchType').attr('value');
        objectValue = $('input#globalSearchInput').attr('value');

        if(objectType && objectValue)
        {
            var reg = /[^0-9]/;
            if(reg.test(objectValue) || objectType == 'all')
            {
                location.href = createLink('search', 'index') + (config.requestType == 'PATH_INFO' ? '?' : '&') + 'words=' + objectValue;
            }
            else
            {
                var types = objectType.split('-');
                var searchModule = types[0];
                var searchMethod = typeof(types[1]) == 'undefined' ? 'view' : types[1];

                location.href = createLink(searchModule, searchMethod, "id=" + objectValue);
            }
        }
    }
});

/* Initialize global search. */
$(function()
{
    var reg = /[^0-9]/;
    var $searchbox    = $('#searchbox');
    var $typeSelector = $searchbox.find('.input-group-btn');
    var $dropmenu     = $typeSelector.children('.dropdown-menu');
    var $searchQuery  = $('#globalSearchInput');
    var searchType    = $('#searchType').val();

    var toggleMenu = function(show)
    {
        $searchbox.toggleClass('open', show);
        $dropmenu.toggleClass('show', show).toggleClass('in', show);
        if(show) $dropmenu.show();
        else $dropmenu.hide();
    };

    var hideMenu = function(){toggleMenu(false);};

    var refreshMenu = function()
    {
        var val        = $searchQuery.val();
        var searchType = changeSearchObject();
        if(val !== null && val !== "")
        {
            var isQuickGo = !reg.test(val);
            $dropmenu.toggleClass('show-quick-go', isQuickGo);
            var $typeAll = $dropmenu.find('li.search-type-all > a');
            $typeAll.html(searchAB + ' <span>"' + val + '"</span>');
            if(isQuickGo)
            {
                $typeAll.closest('li').removeClass('active');
                $dropmenu.removeClass('with-active').find('li:not(.search-type-all) > a').each(function()
                {
                    var $this = $(this);
                    var isActiveType = $this.data('value') === searchType && searchType !== 'all';
                    $this.closest('li').toggleClass('selected active', isActiveType);
                    $this.html($this.data('name') + ' <span>#' + (val.length > 4 ? (val.substr(0, 4) + '...') : val) + "</span>");
                    if(isActiveType) $dropmenu.addClass('with-active');
                });
            }
            else
            {
                $dropmenu.find('li.active').removeClass('active');
                $typeAll.closest('li').addClass('active');
            }
            toggleMenu(true);
        }
        else
        {
            hideMenu();
        }
    };

    $dropmenu = $dropmenu.appendTo($searchbox);
    $dropmenu.on('click', 'a', function(e)
    {
        $('#searchType').val($(this).data('value'));
        $.gotoObject();
        e.stopPropagation();
    }).find('li > a').each(function()
    {
        var $this = $(this);
        $this.attr('data-name', $this.text());
    });

    var $allItem = $dropmenu.find('li > a[data-value="all"]');
    if($allItem.length)
    {
        $allItem.closest('li').addClass('search-type-all').prependTo($dropmenu);
    }

    $searchQuery.on('change keyup paste input propertychange', refreshMenu).on('focus', function()
    {
        setTimeout(refreshMenu, 300);
    });

    $(document).on('click', hideMenu);

    $(document).on('click', function()
    {
        $("#upgradeContent").hide();
    });

    $("#upgradeContent").click(function(event)
    {
        event.stopPropagation();
    });

    $("#proLink").click(function(event)
    {
        var $upgradeContent = $('#upgradeContent').toggle();
        if(!$upgradeContent.is(':hidden'))
        {
            getLatestVersion();
            event.stopPropagation();
        }
    });
});

/* Change the search object according to the module and method. */
function changeSearchObject()
{
    var tabInfo = $.tabs.getLastTab();
    var tabPageModuleName = tabInfo.$iframe[0].contentWindow.config.currentModule;
    var tabPageMethodName = tabInfo.$iframe[0].contentWindow.config.currentMethod;

    var searchType = tabPageModuleName;
    if(tabPageModuleName == 'product' && tabPageMethodName == 'browse') var searchType = 'story';

    var projectMethod = 'task|story|bug|build';
    if(tabPageModuleName == 'project' && projectMethod.indexOf(tabPageMethodName) != -1) var searchType = tabPageMethodName;

    if(tabPageModuleName == 'my' || tabPageModuleName == 'user') var searchType = tabPageMethodName;

    if(searchObjectList.indexOf(searchType) == -1) var searchType = 'bug';

    if(searchType == 'program')   var searchType = 'program-pgmproduct';
    if(searchType == 'project')   var searchType = 'program-index';
    if(searchType == 'execution') var searchType = 'project-view';

    $("#searchType").val(searchType);
    return searchType;
}

function getLatestVersion()
{
    $('#globalSearchInput').click();
    $('#upgradeContent').toggle();
}
