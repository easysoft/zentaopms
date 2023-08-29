/**
 * @param {{side?: 'left' | 'right', toggle?: boolean, container?: HTMLElement}=} options
 */
function toggleSidebar(options) {
    const {side = 'left', toggle, container = document.body} = options || {};
    container.classList.toggle(`hide-sidebar-${side}`, typeof toggle === 'boolean' ? !toggle : undefined);
}

zui.toggleSidebar = toggleSidebar;

zui.bus.on('zt_toggleSidebar', (event) => {
    toggleSidebar(event.detail);
});
