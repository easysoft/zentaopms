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
            {
                text: langData.add,
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