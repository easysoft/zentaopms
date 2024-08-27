/**
 * @type {string[]}
 */
const allMainNavbarItemNames = [];

/**
 * @type {Map<string, any>}
 */
const allMainNavbarItemMap = new Map();

$(document).ready(
    function()
    {
        allMainNavbarItemNames.length = 0;
        allMainNavbarItemMap.clear();

        if (typeof allMainNavbarItems !== 'undefined')
        {
            for(const item of allMainNavbarItems)
            {
                allMainNavbarItemNames.push(item['data-id']);
                allMainNavbarItemMap.set(item['data-id'], item);
            }
        }
    }
);

/**
 * Get current main navbar items data.
 *
 * @returns {Array<{name: string; order: number;}>}
 */
function getCurrentMainNavbarItems()
{
    const items = [];
    const $nav = $('#mainNavbar .nav');
    $nav.children().each(
        function(index, element)
        {
            const $elm = $(element);
            $a = $elm.find('a');
            items.push(
                {
                    name: $a.attr('data-id'),
                    order: index * 5
                }
            );
        }
    );
    return items;
}

/**
 * Generate main menu nav items to be added.
 *
 * @param {(name: string) => void} onClick
 * @returns {Array<{text: string; onClick: () => void;}>}
 */
function generateAddMainNavbarItems(onClick)
{
    const items = [];
    const allMainNavbarItemIDSet = new Set(allMainNavbarItemMap.keys());
    const curMainNavbarItems = getCurrentMainNavbarItems();
    for(const {name} of curMainNavbarItems)
    {
        allMainNavbarItemIDSet.delete(name);
    }

    if(allMainNavbarItemIDSet.size === 0) return items;
    for(const name of allMainNavbarItemIDSet)
    {
        const item = allMainNavbarItemMap.get(name);
        items.push(
            {
                text: item.text,
                onClick: () => onClick(name),
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
    if($item.is('.active')) return false;

    const $navbarActiveItem = $('#navbar .nav .active');
    if($item.attr('href') === $navbarActiveItem.attr('href')) return false;

    return true;
}

$(document).on(
    'contextmenu',
    '#mainNavbar .nav-item > a',
    function(event)
    {
        const $item        = $(this);
        const $nav         = $('#mainNavbar .nav');
        const isMoving     = $nav.is('[z-use-sortable]');
        const hideDisabled = !canHideCurrentNavbar($item);
        const $li          = $item.closest('li');
        const menu         = $item.data('group');
        const customUrl    = $.createLink('custom', 'ajaxSetMenu');
        const itemsToAdded = generateAddMainNavbarItems((name) => {
            const item = allMainNavbarItemMap.get(name);
            const $a = $('<a></a>')
                .attr('href', item.url)
                .attr('data-id', item['data-id'])
                .attr('data-app', item['data-app'])
                .append(`<span class="text">${item.text}</span>`);
            if(item.badge)
            {
                $a.append(`<span class="${item.badge.class}">${item.badge.text}</span>`);
            }
            const $navItem = $('<li class="nav-item item"></li>');
            $navItem.append($a);
            $li.after($navItem);
        });
        const items = [
            isMoving
                ? {
                    text: langData.save,
                    onClick: () => {
                        $item.closest('.nav').zui().destroy();
                        const items = getCurrentMainNavbarItems();
                        $.ajaxSubmit({url: customUrl, data: {menu: menu, items: JSON.stringify(items)}});
                    }
                }
                : {
                    text: langData.sort,
                    onClick: () => {
                        const sortable = new zui.Sortable(
                            '#mainNavbar .nav',
                            {
                                animation: 150,
                                ghostClass: 'bg-primary-pale',
                                onEnd(e) {
                                    console.log('End of drag', e);
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
                        const items = getCurrentMainNavbarItems();
                        $.ajaxSubmit({url: customUrl, data: {menu: menu, items: JSON.stringify(items)}});
                    }
            },
            itemsToAdded.length === 0
                ? {
                    text: langData.add,
                    disabled: true,
                }
                : {
                    text: langData.add,
                    items: itemsToAdded,
                }
        ];

        zui.ContextMenu.show(
            {
                hideOthers: true,
                element: $item[0],
                placement: 'bottom-start',
                items: items,
                event: event,
                onClickItem: (info) => info.event.preventDefault()
            }
        );
        event.preventDefault();
    }
);
