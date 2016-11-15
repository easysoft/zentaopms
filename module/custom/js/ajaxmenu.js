$(function()
{
    var currentModule = startMenu['module'],
        currentMethod = startMenu['method'],
        $mainMenu     = $('#mainmenu'),
        $moduleMenu   = $('#modulemenu'),
        $featureMenu  = $('#featurebar'),
        $loadingIcon  = $('#loadingIcon'),
        $menuEditor   = $('#menuEditor');
    var menuConfig    =
    {
        'main':    null,
        'module':  {},
        'feature': {}
    };

    var updateConfig = function($menu)
    {
        var moduleName = $menu.data('module'), methodName = $menu.data('method');
        var menu = [];
        $menu.children('.nav').children('li:not(.drag-shadow)').each(function()
        {
            var $e = $(this);
            var item = $e.children('a').data('menu');
            item.order = $e.attr('data-order');
            menu.push(item);
        });
        if(moduleName === 'main') menuConfig.main = menu;
        else if(methodName) menuConfig['feature'][moduleName][methodName] = menu;
        else menuConfig['module'][moduleName] = menu;
    }

    var updateMenu = function(moduleName, methodName, items)
    {
        var $menu = moduleName === 'main' ? $mainMenu : (methodName ? $featureMenu : $moduleMenu);
        var items = items || (moduleName === 'main' ? menuConfig.main : (methodName ? menuConfig['feature'][moduleName][methodName] : menuConfig['module'][moduleName]));

        $menu.data({'module': moduleName, 'method': methodName});

        if(items)
        {
            var $nav = $('<ul class="nav"/>');
            $.each(items, function(idx, item)
            {
                if(!item.text || item.fixed) return;
                var $a = $('<a href="#"/>').html(item.text).data('menu', item).append('<i class="item-hidden-icon icon icon-eye-close"></i>');
                $('<li/>').attr('data-id', item.name).toggleClass('right', item.float === 'right').toggleClass('menu-hidden', !!item.hidden).append($a).appendTo($nav);
            });
            $nav.sortable({finish: function(e)
            {
                updateConfig(e.element.closest('nav'));
            }});
            $menu.children('ul').replaceWith($nav);
        }
        else
        {
            $menu.children('ul').empty();
        }
    };

    var activeCurrent = function(moduleName, methodName)
    {
        currentModule = moduleName;
        currentMethod = methodName;
        $mainMenu.find('li.active').removeClass('active');
        $mainMenu.find('li[data-id="' + moduleName + '"]:not(.right)').addClass('active');
        $moduleMenu.find('li.active').removeClass('active');
        $moduleMenu.find('li[data-id="' + methodName + '"]:not(.right)').addClass('active');
    };

    var loadData = function(moduleName, methodName, type, callback)
    {
        type     = type || '';
        var link = createLink('custom', 'ajaxGetMenu', 'module=' + moduleName + '&method=' + methodName + '&type=' + type, 'json');
        $loadingIcon.addClass('active');
        $.get(link, function(data)
        {
            if(data.result === 'success' && data.menu)
            {
                var menu = data.menu;
                if(menu['main'] && !menuConfig['main'])
                {
                    menuConfig['main'] = menu['main'];
                }
                if(menu['module'] && !menuConfig['module'][moduleName])
                {
                    menuConfig['module'][moduleName] = menu['module'];
                }
                if(menu['feature'] !== undefined && (!menuConfig['feature'][moduleName] ||  menuConfig['feature'][moduleName][methodName] === undefined))
                {
                    if(!menuConfig['feature'][moduleName]) menuConfig['feature'][moduleName] = {};
                    menuConfig['feature'][moduleName][methodName] = menu['feature'];
                }
                activeCurrent(moduleName, methodName);
                callback && callback(true, data);
            }
            else 
            {
                callback && callback(false, data);
            }
            $loadingIcon.removeClass('active');
        }, 'json');
    };

    var formatMenuConfig = function(items)
    {
        return $.map(items, function(item)
        {
            var data = {name: item.name, order: parseInt(item.order)};
            if(item.hidden) data.hidden = true;
            if(item.float) data.float = item.float;
            return data;
        });
    };

    $menuEditor.on('mouseenter', '.nav > li:not(.drag-shadow) > a', function()
    {
        var $a   = $(this);
        var item = $a.data('menu');
        var $menu = $a.closest('nav');

        if($menu.is('#mainmenu'))
        {
            var moduleName = item.name;
            if(moduleName !== currentModule)
            {
                var methodName = item.link['method'];
                if(menuConfig['module'][moduleName])
                {
                    updateMenu(moduleName);
                    if(menuConfig['feature'][moduleName] && menuConfig['feature'][moduleName][methodName] !== undefined)
                    {
                        updateMenu(moduleName, methodName);
                        activeCurrent(moduleName, methodName);
                    }
                    else
                    {
                        loadData(moduleName, methodName, 'all', function(result)
                        {
                            if(result)
                            {
                                updateMenu(moduleName, methodName);
                                activeCurrent(moduleName, methodName);
                            }
                        });
                    }
                }
                else
                {
                    loadData(moduleName, methodName, 'all', function(result)
                    {
                        if(result)
                        {
                            updateMenu(moduleName);
                            updateMenu(moduleName, methodName);
                            activeCurrent(moduleName, methodName);
                        }
                    });
                }
            }
        }
        else if($menu.is('#modulemenu'))
        {
            var moduleName = item.link ? item.link['module'] : $menu.data('module');
            var methodName = item.link ? item.link['method'] : item.name;
            if(methodName !== currentMethod)
            {
                if(menuConfig['feature'][moduleName] && menuConfig['feature'][moduleName][methodName] !== undefined)
                {
                    updateMenu(moduleName, methodName);
                    activeCurrent($menu.data('module'), item.name);
                }
                else
                {
                    loadData(moduleName, methodName, 'all', function(result)
                    {
                        if(result)
                        {
                            updateMenu(moduleName, methodName);
                            activeCurrent($menu.data('module'), item.name);
                        }
                    });
                }
            }
        }
    }).on('click', '.nav > li > a', function()
    {
        var $a         = $(this);
        var item       = $a.data('menu');
        var $menu      = $a.closest('nav');
        var moduleName = item.link && item.link['module'] ? item.link['module'] : item.name;
        var methodName = item.link && item.link['method'] ? item.link['method'] : '';
        item.hidden    = !!!item.hidden;
        if($menu.is('#modulemenu'))
        {
            moduleName = currentModule;
            methodName = item.name;
        }
        $a.parent().toggleClass('menu-hidden', item.hidden);
        updateConfig($menu);
    });

    $('#saveMenuBtn').click(function()
    {
        var postData = [{'module': 'main', value: formatMenuConfig(menuConfig.main)}];
        $.each(menuConfig['module'], function(moduleName, items)
        {
            postData.push({'module': moduleName, value: formatMenuConfig(items)});
        });

        $.each(menuConfig['feature'], function(moduleName, feature)
        {
            if(!feature) return;
            $.each(feature, function(methodName, items)
            {
                if(items) postData.push({'module': moduleName, 'method': methodName, value: formatMenuConfig(items)});
            });
        });

        postData = $.map(postData, JSON.stringify);

        $.post(createLink('custom', 'ajaxSetMenu'), {menus: postData}, function(data)
        {
            if(data.result === 'success') window.parent.location.reload()
            if(data.message) alert(data.message);
        }, 'json').error(function(e) {alert(lang.timeout);});
    }).on('mouseenter', function() {$menuEditor.addClass('preview')})
    .on('mouseout', function() {$menuEditor.removeClass('preview')});

    loadData(currentModule, currentMethod, 'all', function(result)
    {
        if(result)
        {
            updateMenu('main');
            updateMenu(currentModule);
            updateMenu(currentModule, currentMethod);
            activeCurrent(currentModule, currentMethod);
        }
    });

    $('#closeModalBtn').click(function()
    {
        window.parent.$.closeModal();
    });
});
