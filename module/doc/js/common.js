function showLibMenu()
{
    var $item = $('#currentItem');
    var $li   = $item.closest('li');
    var $menu = $('#dropMenu');

    if($li.hasClass('show')) {$li.removeClass('show'); return;}
    var $search = $('#searchLib');
    if(!$li.data('showagain'))
    {
        $menu.addClass('loading');
        var isBigMode = false;
        var items = {};
        var itemIdSeed = $.zui.uuid();
        var orderSeed = 1;
        $.getJSON(createLink('doc', 'ajaxGetAllLibs'), function(data)
        {
            $menu.removeClass('loading');

            var $list = $('#libMenuProductGroup > .lib-menu-list');
            var productCount = 0, projectCount = 0, customCount = 0;
            $.each(data.product, function(idx, product)
            {
                $list.append('<div class="list-menu-item-heading" data-status="' + product.status + '" data-product="' + product.id + '">[ ' + product.name + ' ]</div>');
                $.each(product.libs, function(libId, libName)
                {
                    var url = libId === 'files' ? createLink('doc', 'showFiles', 'type=product&objectID=' + product.id) : createLink('doc', 'browse', 'libID=' + libId);
                    var url = libId === 'project' ? createLink('doc', 'allLibs', 'type=project&extra=' + product.id) : url;
                    var itemId = 'lib-' + itemIdSeed++;
                    $list.append('<a data-order="' + (orderSeed++) + '" data-status="' + product.status + '" title="' + product.name + '/' + libName + '" id="' + itemId + '" href="' + url + '" class="list-menu-item" data-type="product" data-product="' + product.id + '" data-id="' + libId + '">' + libName + '</a>');
                    items[itemId] = {type: 'product', id: libId, name: libName, objectId: product.id, product: product.name, search: (libName + ' ' + product.name).toLowerCase()};
                    productCount++;
                });
                $list.append('<div class="list-menu-item-footer" data-status="' + product.status + '" data-product="' + product.id + '"></div>');
            });
            $list.toggleClass('lib-list-bg', productCount > 4);

            $list = $('#libMenuProjectGroup > .lib-menu-list');
            $.each(data.project, function(idx, project)
            {
                $list.append('<div class="list-menu-item-heading" data-status="' + project.status + '" data-project="' + project.id + '">[ ' + project.name + ' ]</div>');
                $.each(project.libs, function(libId, libName)
                {
                    var url = libId === 'files' ? createLink('doc', 'showFiles', 'type=project&objectID=' + project.id) : createLink('doc', 'browse', 'libID=' + libId);
                    var itemId = 'lib-' + itemIdSeed++;
                    $list.append('<a data-order="' + (orderSeed++) + '" data-status="' + project.status + '" title="' + project.name + '/' + libName + '" id="' + itemId + '" href="' + url + '" class="list-menu-item" data-type="project" data-project="' + project.id + '" data-id="' + libId + '">' + libName + '</a>');
                    items[itemId] = {type: 'project', id: libId, name: libName, objectId: project.id, project: project.name, search: (libName + ' ' + project.name).toLowerCase()};
                    projectCount++;
                });
                $list.append('<div class="list-menu-item-footer" data-status="' + project.status + '" data-project="' + project.id + '"></div>');
            });
            $list.find('[data-status="done"]').addClass('hidden');
            $list.toggleClass('lib-list-bg', projectCount > 4);

            $list = $('#libMenuCustomGroup > .lib-menu-list');
            $.each(data.custom, function(libId, libName)
            {
                var url = createLink('doc', 'browse', 'libID=' + libId);
                var itemId = 'lib-' + itemIdSeed++;
                $list.append('<a data-order="' + (orderSeed++) + '" title="' + libName + '" id="' + itemId + '" href="' + url + '" class="list-menu-item" data-type="custom" data-id="' + libId + '">' + libName + '</a>');
                items[itemId] = {type: 'custom', id: libId, name: libName, search: libName.toLowerCase()};
                customCount++;
            });
            $list.toggleClass('lib-list-bg', customCount > 4);

            var $items = $menu.find('.list-menu-item');
            $items.filter('[data-id="' + $item.data('libId') + '"]').addClass('current');
            $items.first().addClass('active');

            $menu.toggleClass('lib-menu-lg', productCount > 9 || projectCount > 9 || customCount > 9);

            var lastSearchKey = $search.val();
            $search.on('change keyup paste input propertychange', function()
            {
                var searchKey = $search.val();
                if(searchKey == lastSearchKey) return;
                lastSearchKey = searchKey;
                $menu.find('.list-menu-item.active').removeClass('active');
                if(searchKey)
                {
                    var $menuHeadings = $menu.find('.list-menu-item-heading').addClass('hidden');
                    var $menuFooters  = $menu.find('.list-menu-item-footer').addClass('hidden');
                    searchKey = searchKey.toLowerCase();
                    $.each(items, function(itemId, item)
                    {
                        var isMatch = item.search.indexOf(searchKey) > -1 || '@' + item.type === searchKey || item.id === searchKey;
                        $('#' + itemId).toggleClass('hidden', !isMatch);
                        if(isMatch && item.type !== 'custom' && item.objectId)
                        {
                            $menuHeadings.filter('[data-' + item.type + '="' + item.objectId + '"]').removeClass('hidden');
                            $menuFooters.filter('[data-' + item.type + '="' + item.objectId + '"]').removeClass('hidden');
                        }
                    });
                }
                else
                {
                    $menu.find('.list-menu-item.hidden,.list-menu-item-heading.hidden,.list-menu-item-footer.hidden').removeClass('hidden');
                    var hasActive = ($('.lib-menu-project-done').hasClass('active'));
                    if(hasActive)
                    {
                        $('#libMenuProjectGroup > .lib-menu-list').find('[data-status!="done"]').addClass('hidden');
                    }
                    else
                    {
                        $('#libMenuProjectGroup > .lib-menu-list').find('[data-status="done"]').addClass('hidden');
                    }
                }
                $menu.find('.list-menu-item:not(.hidden)').first().addClass('active');
            }).on('keydown', function(e)
            {
                $items = $menu.find('.list-menu-item');

                var code = e.which;
                var $this = $items.filter('.list-menu-item.active');
                if(code === 38) // up
                {
                    $this.removeClass('active');
                    if($this.length)
                    {
                        var order = $this.data('order') - 1;
                        if(order > 0)
                        {
                            var $prev = $items.filter('.list-menu-item[data-order="' + order + '"]');
                            while($prev.hasClass('hidden') && order > 1)
                            {
                                order -= 1;
                                $prev = $items.filter('.list-menu-item[data-order="' + order + '"]');
                            }
                            if($prev.length)
                            {
                                $prev.addClass('active');
                                return;
                            }
                        }
                    }
                    $items.last(':not(.hidden)').addClass('active');
                }
                else if(code === 40) // down
                {
                    $this.removeClass('active');
                    if($this.length)
                    {
                        var order = $this.data('order') + 1;
                        if(order <= $items.length)
                        {
                            var $next = $items.filter('.list-menu-item[data-order="' + order + '"]');
                            while($next.hasClass('hidden') && order <= $items.length)
                            {
                                order += 1;
                                $next = $items.filter('.list-menu-item[data-order="' + order + '"]');
                            }
                            if($next.length)
                            {
                                $next.addClass('active');
                                return;
                            }
                        }
                    }
                    $items.first(':not(.hidden)').addClass('active');
                }
                else if(code === 13) // enter
                {
                    if($this.length) window.location.href = $this.attr('href');
                }
            });

            $menu.on('mouseenter', '.list-menu-item', function()
            {
                $menu.find('.list-menu-item.active').removeClass('active');
                $(this).addClass('active');
            });

            $menu.on('click', '.lib-menu-list-heading', function()
            {
                var libType = $(this).data('type');
                var showFilter = $menu.attr('data-filter') != libType;
                $menu.attr('data-filter', showFilter ? libType : null);
            });
            $menu.on('click', '.lib-menu-project-done', function()
            {
                var hasActive = ($(this).hasClass('active'));
                if(hasActive)
                {
                    $(this).removeClass('active');
                    $('#libMenuProjectGroup > .lib-menu-list').find('[data-status!="done"]').removeClass('hidden');
                    $('#libMenuProjectGroup > .lib-menu-list').find('[data-status="done"]').addClass('hidden');
                }
                else
                {
                    $(this).addClass('active');
                    $('#libMenuProjectGroup > .lib-menu-list').find('[data-status!="done"]').addClass('hidden');
                    $('#libMenuProjectGroup > .lib-menu-list').find('[data-status="done"]').removeClass('hidden');
                }
            });
        });
        $li.data('showagain', true);
        $li.on('click', function(e){e.stopPropagation();});
        $(document).click(function(){$li.removeClass('show');});
    }
    $menu.find('.lib-menu-list').css('max-height', $(window).height() - 210);
    $li.addClass('show');
    $search.focus();
}

function loadModules(libID)
{
    link = createLink('doc', 'ajaxGetModules', 'libID=' + libID);
    $('#moduleBox').load(link, function(){$('#moduleBox').find('select').chosen(defaultChosenOptions)});
}

function toggleAcl(acl)
{
    if(acl == 'custom')
    {
        $('#whiteListBox').removeClass('hidden');
    }
    else
    {
        $('#whiteListBox').addClass('hidden');
    }
}

$(document).ready(function()
{
    $('[data-id="create"] a').modalTrigger({type: 'iframe', width: 800});
    $('#modulemenu .nav li').removeClass('active');
    if(typeof(type) != 'undefined') $('#modulemenu .nav li[data-id="' + type + '"]').addClass('active');

    $('.libs-group').sortable(
    {
        trigger:  '.lib',
        selector: '.lib',
        finish:   function() 
        {
            var orders = {};
            var orderNext = 1;

            $('.libs-group .lib').not('.files').not('.addbtn').each(function()
            {
                orders[$(this).data('id')] = orderNext ++;
            })

            $.post(createLink('doc', 'sort'), orders, function(data)
            {
                if(data.result == 'success')
                {
                    return location.reload();
                }
                else
                {
                    alert(data.message);
                    return location.reload();
                }
            }, 'json');
        }

    });
});
