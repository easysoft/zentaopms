$(function()
{
    initModule();
    setHeight();
    $(window).resize(setHeight);
});

/**
 * Set pane height.
 *
 * @access public
 * @return void
 */
function setHeight()
{
    var paneHeight = $(window).height() - 90;
    $('#sidebar .module-tree,#mainContent .module-content').css('height', paneHeight);
}
/**
 * Init module tree by zui.
 *
 * @access public
 * @return void
 */
function initModule()
{
    $('#moduleTree').tree(
    {
        data: moduleTree,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            console.log(item);
            $li.append('<a data-module="' + item.key + '" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# title="' + item.title + '">' + item.title + '</a>');
            if (item.active) $li.addClass('active open in');
        }
    });

    $('#moduleTree').on('click', 'a', function(e)
    {
        var target = $(e.target);
        if (target.attr('data-has-children') === 'true') return;
        self.location.href = createLink('dev', 'api', 'module=' + target.attr('data-module'));
    })
}
