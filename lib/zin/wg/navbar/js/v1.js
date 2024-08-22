function getAllNavbarItems() 
{
    const items = [];
    for(const item of allNavbarItems)
    {
        if(item.type === 'divider') 
        {
            items.push('divider');
            continue;
        }

        if (item.type === 'dropdown') 
        {
            item.push(item.id);
            continue;
        }

        item.push(item['data-id']);
    }
    return items;
}

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

$(document).on(
    'contextmenu', 
    '#navbar .nav-item:not(.nav-dropdown) > a, #navbar .nav-divider', 
    function(event) 
    {
        const $item = $(this);
        const $nav = $('#navbar .nav');
        const isMoving = $nav.is('[z-use-sortable]');
        const hideDisabled = $item.is('.active');
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
                            $item.remove();
                        }
                        else
                        {
                            $item.remove();
                        }
                    }
            },
            {
                text: langData.add,
                onClick: () => {
                }
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