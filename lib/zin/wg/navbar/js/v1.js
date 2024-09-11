/**
 * @type {string[]}
 */
const allNavbarItemNames = [];

/**
 * @type {Map<string, any>}
 */
const allNavbarItemMap = new Map();

$(document).ready(
    function()
    {
        allNavbarItemNames.length = 0;
        allNavbarItemMap.clear();

        for(const item of allNavbarItems)
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
    }
);

/**
 * Get current navbar items data.
 *
 * @returns {Array<{name: string; order: number;}>}
 */
function getCurrentNavbarItems()
{
    const items = [];
    const $nav  = $('#navbar .nav');
    $nav.children().each(
        function(index, element)
        {
            const $elm = $(element)
            const $a   = $elm.find('a');
            items.push(
                {
                    name: $elm.is('.nav-divider') ? 'divider' : ($a.attr('data-id') || $a.attr('id')),
                    order: index * 5
                }
            );
        }
    );
    return items;
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
    if(itemUrl.includes(appDefaultUrl)) return false;
    return true;
}

/**
 * Get menu name by items and app.
 *
 * @param {string} app
 * @returns {string}
 */
function getMenuName(app)
{
    if(typeof projectModel !== 'undefined')
    {
        return `project-${projectModel}`;
    }

    if(isHomeMenu)
    {
        return `${app}-home`;
    }

    if(app == 'admin')
    {
        return `admin-${adminMenuKey}`;
    }
    return app;
}

$(document).on(
    'contextmenu',
    '#navbar .nav-item:not(.nav-dropdown) > a, #navbar .nav-divider',
    function(event)
    {
        const $item        = $(this);
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
                        $li.remove();
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
                            loadCurrentPage('#navbar');
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
            }
        );
        event.preventDefault();
    }
);

/**
 * Save navbar to server.
 */
function saveNavbarToServer()
{
    const url = $.createLink('custom', 'ajaxSetMenu');
    const items     = getCurrentNavbarItems();
    const app       = $.apps.getLastApp().code;
    const menu      = getMenuName(app);
    $.ajaxSubmit({url, data: {menu, items: JSON.stringify(items)}});
}

/**
 * Restore navbar to server.
 */
function restoreNavbarToServer(options = {})
{
    const url  = $.createLink('custom', 'ajaxRestoreMenu');
    const app  = $.apps.getLastApp().code;
    const menu = getMenuName(app);
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
