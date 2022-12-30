if(showFeature)
{
    /* Show features dialog. */
    new $.zui.ModalTrigger({url: $.createLink('zahost', 'introduction'), type: 'iframe', width: 900, className: 'showFeatures', showHeader: false, backdrop: 'static'}).show();
}
