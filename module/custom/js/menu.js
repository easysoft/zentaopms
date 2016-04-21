$(function()
{
    var currentModule = startMenu['module'],
        currentMethod = startMenu['method'];
    var $mainMenu    = $('#mainmenu'),
        $moduleMenu  = $('#modulemenu'),
        $featureMenu = $('#featurebar'),
        $loadingIcon = $('#loadingIcon'),
        $menuEditor  = $('#menuEditor');
    var menuConfig = {
        'main': null,
        'module': {},
        'feature': {}
    };

    var updateMenu = function($menu, items)
    {
        if(items)
        {
            var $nav = $('<ul class="nav"/>');
            $.each(items, function(idx, item)
            {
                if(!item.text || item.fixed) return;
                var $a = $('<a href="#"/>').html(item.text).data('menu', item).append('<i class="item-hidden-icon icon icon-eye-close"></i>');
                $('<li/>').attr('data-id', item.name).toggleClass('right', item.float === 'right').toggleClass('menu-hidden', !!item.hidden).append($a).appendTo($nav);
            });
            $nav.sortable();
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
        $mainMenu.find('li[data-id="' + moduleName + '"]').addClass('active');
        $moduleMenu.find('li.active').removeClass('active');
        $moduleMenu.find('li[data-id="' + methodName + '"]').addClass('active');
    };

    var loadData = function(moduleName, methodName, type)
    {
        type     = type || '';
        var link = createLink('custom', 'menu', 'module=' + moduleName + '&method=' + methodName + '&type=' + type, 'json');
        $loadingIcon.addClass('active');
        $.get(link, function(data)
        {
            console.log(data);
            if(data.result === 'success' && data.menu)
            {
                var menu = data.menu;
                if(menu['main'])
                {
                    updateMenu($mainMenu, menu['main']);
                    menuConfig['main'] = menu['main'];
                }
                if(menu['module'])
                {
                    updateMenu($moduleMenu, menu['module']);
                    menuConfig['module'][moduleName] = menu['module'];
                }
                if(menu['feature'] !== undefined)
                {
                    updateMenu($featureMenu, menu['feature']);
                    if(!menuConfig['feature'][moduleName]) menuConfig['feature'][moduleName] = {};
                    menuConfig['feature'][moduleName][methodName] = menu['feature'];
                }
            }
            activeCurrent(moduleName, methodName);
            $loadingIcon.removeClass('active');
        }, 'json');
    };

    $menuEditor.on('mouseenter', '.nav > li > a', function()
    {
        // var $a   = $(this);
        // var item = $a.data('menu');
        // var $menu = $a.closest('nav');
        // var moduleName = item.link && item.link['module'] ? item.link['module'] : item.name;
        // var methodName = item.link && item.link['method'] ? item.link['method'] : '';
        // if($menu.is('#modulemenu'))
        // {
        //     moduleName = currentModule;
        //     methodName = item.name;
        // }
        // if(moduleName !== currentModule || methodName !== currentMethod)
        // {
        //     if($menu.is('#mainmenu,#modulemenu'))
        //     {
        //         if(menuConfig['module'][moduleName] !== undefined && menuConfig['feature'][moduleName] && menuConfig['feature'][moduleName][methodName] !== undefined)
        //         {
        //             updateMenu($moduleMenu, menuConfig['module'][moduleName]);
        //             updateMenu($featureMenu, menuConfig['feature'][moduleName][methodName]);
        //             activeCurrent(moduleName, methodName);
        //         }
        //         else
        //         {
        //             loadData(moduleName, methodName, 'all');
        //         }
        //     }
        // }
    }).on('click', '.nav > li > a', function()
    {
        var $a   = $(this);
        var item = $a.data('menu');
        var $menu = $a.closest('nav');
        var moduleName = item.link && item.link['module'] ? item.link['module'] : item.name;
        var methodName = item.link && item.link['method'] ? item.link['method'] : '';
        if($menu.is('#modulemenu'))
        {
            moduleName = currentModule;
            methodName = item.name;
        }
        $a.parent().toggleClass('menu-hidden');
    });

    $('#saveMenuBtn').click(function()
    {
        console.log('save', menuConfig);
    }).on('mouseenter', function() {$menuEditor.addClass('preview')})
    .on('mouseout', function() {$menuEditor.removeClass('preview')});

    loadData(currentModule, currentMethod, 'all');
});
