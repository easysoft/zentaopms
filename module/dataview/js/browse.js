$(function()
{
    if(table)
    {
        $('.dataview-' + table).addClass('active');

        var $tree = $('#modules').tree({name:'tree-group'});
        var tree  = $tree.data('zui.tree');
        tree.expand($('.dataview-' + table).parents('li'));
    }

    $(document).on('click','.query-view', function()
    {
        var url    = createLink('dataview', 'query', 'id=' + table);
        var result = true;

        if(dataview.used) result = confirm(warningDesign);
        if(result) window.location.href = url;
    });
});

/**
 * Locate page.
 *
 * @param string module
 * @param string method
 * @param string params
 * @access public
 * @return void
 */
function locate(module, method, params)
{
    var link = createLink(module, method, params);
    window.location.href = link;
}
