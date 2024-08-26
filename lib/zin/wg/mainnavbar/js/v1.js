const allMainNavbarItemIDs = [];
const allMainNavbarItemMap = new Map();

$(document).ready(
    function()
    {
        allMainNavbarItemIDs.length = 0;
        allMainNavbarItemMap.clear();

        if (typeof allMainNavbarItems !== 'undefined')
        {
            for(const item of allMainNavbarItems)
            {
                allMainNavbarItemIDs.push(item['data-id']);
                allMainNavbarItemMap.set(item['data-id'], item);
            }
        }
    }
);

function getCurrentMainNavbarItems()
{
    const items = [];
    const $nav = $('#mainNavbar .nav');
    $nav.children().each(
        function(index, element)
        {
            const $elm = $(element);
            $a = $elm.find('a');
            items[index] = $a.attr('data-id');
        }
    );
    return items;
}

function generateAddMainNavbarItems(onClick)
{
    const items = [];
    const allMainNavbarItemIDSet = new Set(allMainNavbarItemMap.keys());
    const curMainNavbarItems = getCurrentMainNavbarItems();
    for(const id of curMainNavbarItems)
    {
        allMainNavbarItemIDSet.delete(id);
    }

    if(allMainNavbarItemIDSet.size === 0) return items;
    for(const id of allMainNavbarItemIDSet)
    {
        const item = allMainNavbarItemMap.get(id);
        items.push(
            {
                text: item.text,
                onClick: () => onClick(id),
            }
        );
    }
    return items;
}

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
        const $item = $(this);
        const $nav = $('#mainNavbar .nav');
        const isMoving = $nav.is('[z-use-sortable]');
        const hideDisabled = !canHideCurrentNavbar($item);
        const $li = $item.closest('li');
        const itemsToAdded = generateAddMainNavbarItems((id) => {
            const item = allMainNavbarItemMap.get(id);
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
            console.log(getCurrentMainNavbarItems());
        });
        const items = [
            isMoving
                ? {
                    text: langData.save,
                    onClick: () => {
                        $item.closest('.nav').zui().destroy();
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
