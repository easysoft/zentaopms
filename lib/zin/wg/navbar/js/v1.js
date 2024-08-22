$(document).on(
    'contextmenu', 
    '#navbar .nav-item > a, #navbar .nav-divider', 
    function(event) 
    {
        console.log(langData);
        const $item = $(this);
        const $nav = $('#navbar .nav');
        const isMoving = $nav.is('[z-use-sortable]');
        const items = [
            isMoving
                ? {
                    text: langData.save,
                    onClick: () => {
                        $item.closest('.nav').zui().destroy();
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
                disabled: $item.is('active'),
                onClick: () => {
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