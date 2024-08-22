const allNavbarItemIDs = [];
const allNavbarItemMap = new Map();

$(document).ready(
    function()
    {
        allNavbarItemIDs.length = 0;
        allNavbarItemMap.clear();

        for(const item of allNavbarItems)
        {
            if(item.type === 'divider')
            {
                allNavbarItemIDs.push('divider');
                continue;
            }

            if (item.type === 'dropdown')
            {
                allNavbarItemIDs.push(item.id);
                allNavbarItemMap.set(item.id, item);
                continue;
            }

            allNavbarItemIDs.push(item['data-id']);
            allNavbarItemMap.set(item['data-id'], item);
        }
        console.log(allNavbarItemMap, allNavbarItemIDs);
    }
);

function getCurrentNavbarItems()
{
    const items = [];
    const $nav = $('#navbar .nav');
    $nav.children().each(
        function(index, element)
        {
            const $elm = $(element)
            if($elm.is('.nav-divider'))
            {
                items[index] = 'divider';
                return;
            }

            const $a = $elm.find('a');
            if($elm.is('.nav-dropdown'))
            {
                items[index] = $a.prop('id');
                return;
            }

            items[index] = $a.data('id');
        }
    );
    return items;
}

function generateAddNavbarItems(onClick)
{
    const items = [
        {
            text: '分割线',
            onClick: () => onClick('divider')
        }
    ];

    const allNavbarItemIDSet = new Set(allNavbarItemMap.keys());
    const curNavbarItems = getCurrentNavbarItems();
    for(const id of curNavbarItems)
    {
        if(id === 'divider') continue;
        allNavbarItemIDSet.delete(id);
    }

    if(allNavbarItemIDSet.size === 0) return items;
    for(const id of allNavbarItemIDSet)
    {
        const item = allNavbarItemMap.get(id);
        items.push(
            {
                text: item.text,
                onClick: () => onClick(id),
            }
        );
    }
    return items;
}

$(document).on(
    'contextmenu',
    '#navbar .nav-item:not(.nav-dropdown) > a, #navbar .nav-divider',
    function(event)
    {
        const $item = $(this);
        const $nav = $('#navbar .nav');
        const isMoving = $nav.is('[z-use-sortable]');
        const hideDisabled = $item.is('.active');
        const $li = $item.closest('li');
        const items = [
            isMoving
                ? {
                    text: langData.save,
                    onClick: () => {
                        $item.closest('.nav').zui().destroy();
                        console.log(getCurrentNavbarItems());
                    }
                }
                : {
                    text: langData.move,
                    onClick: () => {
                        const sortable = new zui.Sortable(
                            '#navbar .nav',
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
                        if($item.is('.nav-divider'))
                        {
                            $li.remove();
                        }
                        else
                        {
                            $li.remove();
                        }
                        console.log(getCurrentNavbarItems());
                    }
            },
            {
                text: langData.add,
                items: generateAddNavbarItems((id) => {
                    if(id === 'divider') return $li.after('<li class="nav-divider item divider"></li>');

                    const item = allNavbarItemMap.get(id);
                    const $a = $('<a></a>')
                        .attr('href', item.url)
                        .attr('target', item.target)
                        .attr('data-id', item['data-id'])
                        .append(`<span class="text">${item.text}</span>`);
                    if(item.class) $a.attr('class', item.class);
                    const $navItem = $('<li class="nav-item item"></li>');
                    $navItem.append($a);
                    $li.after($navItem);
                    console.log(getCurrentNavbarItems());
                }),
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