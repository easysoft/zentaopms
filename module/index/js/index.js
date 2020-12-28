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
        var $menuMainNav = $('#menuMainNav').empty();
        window.menuItems.forEach(function(item)
        {
            if(item === 'divider') return $menuMainNav.append('<li class="divider"></li>');

            var $link= $('<a></a>').attr('href', item.url)
                .attr('data-group', item.group)
                .attr('class', 'show-in-tab')
                .html(item.title);
            $('<li></li>').attr('data-group', item.group)
                .append($link)
                .appendTo($menuMainNav);

            item.icon = $link.find('.icon').attr('class').replace('icon ', '');
            item.text = $link.text().trim();
            groupsMap[item.group] = item;

            if(!defaultTabGroup) defaultTabGroup = item.group;
        });
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
                var moduleName = $.parseLink(url).moduleName;
                group = window.navGroup[moduleName] || moduleName;
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
            var $link= $('<a></a>').attr('href', tab.url)
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
            if((!onlyShowed || tab.show) && lastShowIndex < tab.zIndex)
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
        var tab = openedTabs[group];
        if(!tab) return;

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
            iframe.src = url || iframe.contentWindow.location.href;
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
            var url = $link.hasClass('open-in-tab') ? ($link.attr('href') || $link.data('url')) : '';
            openTab(url, $link.data('group'));
            e.preventDefault();
        }).on('contextmenu', '.open-in-tab,.show-in-tab', function(event)
        {
            var group = $(this).data('group');
            if(!group) return;

            var lang  = window.tabsLang;
            var tab   = openedTabs[group];
            var items = [{label: lang.open, disabled: tab && lastOpenedGroup === group, onClick: function(){showTab(group)}}];
            if(tab)
            {
                items.push({label: lang.reload, onClick: function(){reloadTab(group)}});
                if(group !== 'my') items.push({label: lang.close, onClick: function(){closeTab(group)}});
            }
            $.zui.ContextMenu.show(items, {event: event});
            event.preventDefault();
        });

        /* Redirect or open default tab after document load */
        var defaultOpenUrl = window.defaultOpen;
        if(!defaultOpenUrl)
        {
            if(location.hash.indexOf('#open=') === 0)
            {
                defaultOpenUrl = decodeURIComponent(location.hash.substr(6));
            }
            if(!defaultOpenUrl)
            {
                defaultOpenUrl = $.cookie('open-in-tab');
                if(defaultOpenUrl) $.cookie('open-in-tab', null);
            }
        }

        if(defaultOpenUrl) openTab(defaultOpenUrl);
        else openTab(defaultTabGroup);
    });
}());
