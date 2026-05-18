/**
 * @type {string[]}
 */
const allNavbarItemNames = [];

/**
 * @type {Map<string, any>}
 */
const allNavbarItemMap = new Map();

/**
 * Get current navbar items data.
 *
 * @returns {Array<{name: string; order: number;}>}
 */
function getCurrentNavbarItems()
{
    const items         = [];
    const $nav          = $('#navbar .nav');
    const overflowItems = [];
    $nav.children().each(function(index, element)
    {
        const $elm = $(element);
        if($elm.hasClass('rsh-more')) return;

        const $a       = $elm.find('a');
        const menuItem = {};
        menuItem.name  = $elm.is('.nav-divider') ? 'divider' : ($a.attr('data-id') || $a.attr('id'));
        if(typeof $elm.data('hidden') != 'undefined') menuItem.hidden = true;

        if($elm.hasClass('rsh-overflow-item')) overflowItems.push(menuItem);
        else items.push(menuItem);
    });
    return [...items, ...overflowItems].map((item, index) => ({...item, order: index * 5}));
}

/**
 * Generate navbar items to be added.
 * @param {Cash} $item
 * @param {(item: string) => void} onClick click handler of navbar item.
 * @returns {Array<{text: string; onClick: () => void;}>}
 */
function generateAddNavbarItems($item, onClick)
{
    const items = canAddDivider($item)
        ? [{
            text: langData.divider,
            onClick: () => {
                onClick('divider');
                saveNavbarToServer();
            }
        }]
        : [];

    const allNavbarItemIDSet = new Set(allNavbarItemMap.keys());
    const curNavbarItems = getCurrentNavbarItems();
    for(const {name} of curNavbarItems)
    {
        if(name === 'divider') continue;
        allNavbarItemIDSet.delete(name);
    }

    if(allNavbarItemIDSet.size === 0) return items;
    for(const name of allNavbarItemIDSet)
    {
        const item = allNavbarItemMap.get(name);
        items.push(
            {
                text: item.text,
                onClick: () => {
                    onClick(name);
                    saveNavbarToServer();
                }
            }
        );
    }
    return items;
}

/**
 * Checks whether the current navbar item can be hidden.
 *
 * @param {Cash} $item
 * @returns {boolean}
 */
function canHideCurrentNavbar($item)
{
    if($item.is('.nav-divider')) return true;
    if($item.is('.active')) return false;

    const app = $.apps.getLastApp();
    const appDefaultUrl = app.url;
    const itemUrl = $item.attr('href');
    if(itemUrl && itemUrl.includes(appDefaultUrl)) return false;
    return true;
}

function getMenuName()
{
    const $nav = $('#navbar>.nav');
    return $nav.attr($nav.attr('data-workspace') ? 'data-main-navbar-group' : 'data-navbar-group');
}

/**
 * Save navbar to server.
 */
function saveNavbarToServer(items, menu)
{
    const url = $.createLink('custom', 'ajaxSetMenu');
    items     = items || getCurrentNavbarItems();
    menu      = menu ||getMenuName();
    $.ajaxSubmit({url, data: {menu, items: JSON.stringify(items)}});
}

/**
 * Restore navbar to server.
 */
function restoreNavbarToServer(options = {})
{
    const url  = $.createLink('custom', 'ajaxRestoreMenu');
    const menu = getMenuName();
    $.ajaxSubmit({url, data: {menu}, ...options});
}

/**
 * Check whether current element can add a divider.
 * @param {Cash} $item
 * @returns {boolean}
 */
function canAddDivider($item)
{
    $item = $item.closest('li');
    if($item.is('.divider'))        return false;
    if($item.next().is('.divider')) return false;
    if($item.is(':last-child'))     return false;
    return true;
}

window.handleNavbarContextmenu = function(event, element)
{
    const $item        = $(element);
    const $nav         = $('#navbar .nav');
    const isMoving     = $nav.is('[z-use-sortable]');
    const hideDisabled = !canHideCurrentNavbar($item);
    const $li          = $item.closest('li');
    const toAddedItems = generateAddNavbarItems($item, (name) => {
        if(name === 'divider') return $li.after('<li class="nav-divider item divider"></li>');

        const item = allNavbarItemMap.get(name);
        const $a = $('<a></a>')
            .attr('href', item.url)
            .attr('target', item.target)
            .attr('data-id', item['data-id'])
            .append(`<span class="text">${item.text}</span>`);
        if(item.class) $a.attr('class', item.class);
        const $navItem = $('<li class="nav-item item"></li>');
        $navItem.append($a);
        $li.after($navItem);
        saveNavbarToServer();
    });
    const items = [
        isMoving
            ? {
                text: langData.save,
                onClick: () => {
                    $item.closest('.nav').zui().destroy();
                    saveNavbarToServer();
                }
            }
            : {
                text: langData.sort,
                onClick: () => {
                    const sortable = new zui.Sortable(
                        '#navbar .nav',
                        {
                            animation: 150,
                            ghostClass: 'bg-primary-pale',
                            onSort: () => {
                                saveNavbarToServer();
                            }
                        }
                    );
                }
            },
        {
            text: langData.hide,
            disabled: hideDisabled,
            onClick: hideDisabled
                ? null
                : () => {
                    $li.hasClass('nav-divider') ? $li.remove() : $li.hide().attr('data-hidden', '1');
                    saveNavbarToServer();
                }
        },
        toAddedItems.length === 0
            ? {
                text: langData.add,
                disabled: true,
            }
            : {
                text: langData.add,
                items: toAddedItems,
            },
        {
            text: langData.restore,
            onClick: () => {
                restoreNavbarToServer({
                    onSuccess() {
                        loadCurrentPage('#navbar>*');
                    }
                });
            }
        }
    ];
    if(window.customNavbarContextMenu) window.customNavbarContextMenu.hide();
    window.customNavbarContextMenu = zui.ContextMenu.show(
        {
            element: $item[0],
            placement: 'bottom-start',
            items: items,
            event: event,
            onClickItem: (info) => info.event.preventDefault(),
            onHide: () => window.customNavbarContextMenu = null,
            $optionsFromDataset: false,
        }
    );
    event.preventDefault();
}

window.initPageNavbar = function(navbarItems, workspace, activeMenu, spaceTag, menuOrders)
{
    allNavbarItemNames.length = 0;
    allNavbarItemMap.clear();

    if(Array.isArray(menuOrders) && menuOrders.length)
    {
        const orderMap = new Map();
        for(const item of menuOrders) orderMap.set(item.name, item.order);
        if(!orderMap.has('more')) orderMap.set('more', 99999);
        let lastItem = null;
        navbarItems.forEach((item, index) => {
            item.order = orderMap.get(item.name || item.code || item['data-id']) || (lastItem ? lastItem.order + 1 : 0);
            lastItem = item;
        });
        navbarItems.sort((a, b) => a.order - b.order);
    }

    for(const item of navbarItems)
    {
        if(item.type === 'divider')
        {
            allNavbarItemNames.push('divider');
            continue;
        }

        const name = item['data-id'] || item.id;

        allNavbarItemNames.push(name);
        allNavbarItemMap.set(name, item);
    }

    updateWorkspaceUI(workspace ? {type: workspace, active: activeMenu, items: navbarItems, itemMap: allNavbarItemMap, saveToServer: saveNavbarToServer} : null);
}
