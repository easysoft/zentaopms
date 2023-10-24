$(function()
{
    initModuleTree();
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
    var paneHeight = $(window).height() - 120;
    $('#sidebar .module-tree,#mainContent .module-col,#extendWin').css('height', paneHeight);
    $(' #mainContent .module-content, #editWin').css('height', paneHeight - 6);
}

/**
 * Init module tree by zui.
 *
 * @access public
 * @return void
 */
function initModuleTree()
{
    $('#moduleTree').tree(
    {
        data: moduleTree,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a data-module="' + item.key + '" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# title="' + item.title + '">' + item.title + '</a>');
            if (item.active) $li.addClass('active open in');
        }
    });

    $('#moduleTree').on('click', 'a', function(e)
    {
        var target = $(e.target);
        if (target.attr('data-has-children') === 'true') return;
        $('#extendWin').attr('src', createLink('editor', 'extend', 'moduleDir=' + target.attr('data-module')));

        $(this).closest('.side-col').find('li.active').removeClass('active');
        $(this).parent().addClass('active');
        $(this).parent().parent().parent().addClass('active');
    })

    $firstModule = $('#moduleTree li a:not([data-has-children="true"])');
    if($firstModule.length)
    {
        $firstModule = $firstModule.eq(0);
        $firstModule.closest('li.has-list').addClass('open in');
        $firstModule.trigger('click');
    }
}
